<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Booking;

class ExpireBookingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking_id, $repository;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($booking_id, $repository)
    {
        $this->booking_id = $booking_id;
        $this->repository = $repository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $booking = Booking::find($this->booking_id);
        if (!empty($booking) && $booking->status_id == 1) {
            $booking->status_id = 4;

            $request = new \Illuminate\Http\Request();
            $request->replace(['booking' => $booking]);
            $this->repository->update($request, $booking->id);
        }
    }
}
