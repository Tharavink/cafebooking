<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

use App\Models\Cafe;
use App\Models\Booking;
use App\Models\Waiting;
use App\Models\CafeTable;
use App\Models\User;

use App\Jobs\ExpireBookingJob;

use App\Events\TableStatusUpdated;

use App\Notifications\NotifyTableAvailability;

class BookingRepository extends BaseRepository
{
    protected $model;

    public function __construct(Booking $model) {
        $this->model = $model;
    }

    public function index($request)
    {
        $user = $request->user();
        $bookings = $this->model->with('cafe_table', 'cafe', 'status', 'user');

        if ($user->type_id == 2) {
            $cafe_ids = Cafe::where('owner_id', $user->id)->pluck('id')->toArray();
            $bookings->whereIn('cafe_id', $cafe_ids);

            if ($request->keyword) {
                $keyword = $request->keyword;
                $bookings->whereHas('user', function($user) use($keyword) {
                    $user->where('name', 'like', '%'.$keyword.'%');
                });
            }
        } elseif ($user->type_id == 3) {
            $bookings->where('user_id', $user->id);

            if ($request->keyword) {
                $keyword = $request->keyword;
                $bookings->whereHas('cafe', function($cafe) use($keyword) {
                    $cafe->where('name', 'like', '%'.$keyword.'%');
                });
            }
        }

        if ($request->status_id) {
            $bookings->where('status_id', $request->status_id);
        }

        return $bookings->get();
    }

    public function store($request)
    {
        $user = $request->user();
        if ($request->is_notify) {
            return Waiting::create($request->only('cafe_id', 'user_id', 'number'));
        } else {
            $table = CafeTable::select(DB::raw('(SELECT COUNT(*) FROM cafe_table_slots WHERE table_id=cafe_tables.id AND disallowed=0) as total, cafe_tables.*'))
                                    ->where('cafe_id', $request->cafe_id)
                                    ->where('status_id', 1)
                                    ->having('total', '>=', $request->number)
                                    ->orderBy('total')
                                    ->first();

            if (is_null($table)) {
                return ['info' => 'All tables are full for requested number of persons. Would you like us to notify if the table becomes available?'];
            }
            else {
                $booking_data = $request->all();
                $booking_data['table_id'] = $table->id;
                $booking = $this->model->create($booking_data);

                $tbl = CafeTable::find($table->id);
                $tbl->update(['status_id' => 2]);
                broadcast(new TableStatusUpdated($tbl));

                ExpireBookingJob::dispatch($booking->id, $this)->onConnection('database')->delay(now()->addMinutes(15));

                return $booking->load(['cafe', 'cafe_table', 'user', 'status']);
            }
        }
    }

    public function show($id, $request)
    {
        $user = $request->user();
        $booking = $this->model->with('cafe', 'cafe_table', 'user', 'status')->where('user_id', $user->id)->where('cafe_id', $id)->whereIn('status_id', [1, 2])->get();
        if (is_null($booking)) {
            return ['error' => 'No booking exist for you in this cafe. Please refer to your Booking dashboard for more information.'];
        } else {
            return $booking;
        }
    }

    public function update($request, $id)
    {
        $booking = $this->model->find($id);
        if (is_null($booking)) {
            return ['error' => 'Booking not found'];
        } else {
            $tbl = CafeTable::find($booking->table_id);
            $booking_data = $request->booking;
            if ($request->is_sign_in) {
                $booking->update(['sign_in' => Carbon::now()]);
                $tbl->update(['status_id' => 3]);
                $booking->update(['status_id' => 2]);
            } elseif ($request->is_sign_out) {
                $booking->update(['sign_out' => Carbon::now()]);
                $tbl->update(['status_id' => 1]);
                $booking->update(['status_id' => 3]);

                $this->notifyWaiting($tbl);
            } elseif ($booking_data['status_id'] != $booking->status_id) {
                $booking->update(['status_id' => $booking_data['status_id']]);
                if (in_array($booking_data['status_id'], [3, 4, 5])) {
                    $tbl->update(['status_id' => 1]);

                    $this->notifyWaiting($tbl);
                }
            }
            broadcast(new TableStatusUpdated($tbl));

            return $booking->load(['cafe', 'cafe_table', 'user', 'status']);
        }
    }

    public function notifyWaiting($table)
    {
        $user_ids = Waiting::where('cafe_id', $table->cafe_id)->where('number', '<=', $table->slots->where('disallowed', 0)->count())->pluck('user_id')->toArray();
        $users = User::whereIn('id', $user_ids)->get();

        $table->load(['cafe']);
        Notification::send($users, new NotifyTableAvailability($table));
    }

    public function cancel_waiting($id, $request)
    {
        Waiting::where('user_id', $request->user()->id)->where('cafe_id', $id)->delete();
        $cafe_name = Cafe::find($id)->name;
        return ['success' => 'All your waiting requests for '.$cafe_name.' have been cancelled'];
    }
}