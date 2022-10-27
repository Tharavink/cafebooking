<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Auth;
use PDF;

use App\Models\Cafe;
use App\Models\CafeTable;
use App\Models\CafeTableSlot;

class CafeRepository extends BaseRepository
{
    protected $model;

    public function __construct(Cafe $model) {
        $this->model = $model;
    }

    public function index($request)
    {
        $user = $request->user();
        $cafes = $this->model->with('owner', 'tables.slots', 'tables.status');

        if ($user->type_id == 2) {
            $cafes->where('owner_id', $user->id);
        }

        if ($request->keyword) {
            $keyword = $request->keyword;
            $cafes->where('name', 'like', '%'.$keyword.'%');
        }

        return $cafes->get();
    }

    public function show($id)
    {
        return $this->model->with('tables.slots', 'tables.status')->find($id);
    }

    public function save($request, $id=null)
    {
        $data = $request->all();
        if ($request && $request->newLogo) {
            $file = saveAsFile($request->newLogo);
            $data['logo_id'] = $file->id;
        }
        
        $data['owner_id'] = Auth::id();
        if ($id) {
            $cafe = $this->model->find($id);
            $cafe->update(Arr::only($data, ['name', 'logo_id', 'owner_id']));
        } else {
            $cafe = $this->model->create(Arr::only($data, ['name', 'logo_id', 'owner_id']));
        }
        
        $table_ids = [];
        foreach ($data['tables'] as $key => $tbl_data) {
            if (!empty($tbl_data['id'])) {
                $tbl = CafeTable::find($tbl_data['id']);
                $tbl->update(['cafe_id' => $cafe->id, 'number' => $key + 1]);

                array_push($table_ids, $tbl_data['id']);
            } else {
                $tbl = CafeTable::create([
                    'cafe_id' => $cafe->id,
                    'number' => $key + 1
                ]);
            }

            $slot_ids = [];
            foreach ($tbl_data['slots'] as $key => $slot_data) {
                if (!empty($slot_data['id'])) {
                    $slot = CafeTableSlot::find($slot_data['id']);
                    $slot->update(['table_id' => $tbl->id, 'number' => $slot_data['number'], 'disallowed' => $slot_data['disallowed']]);
    
                    array_push($slot_ids, $slot_data['id']);
                } else {
                    $slot = CafeTableSlot::create(['table_id' => $tbl->id, 'number' => $slot_data['number'], 'disallowed' => $slot_data['disallowed']]);
                }
            }

            if ($id) {
                CafeTableSlot::where('table_id', $tbl->id)->whereNotIn('id', $slot_ids)->delete();
            }
        }
        if ($id) {
            CafeTable::where('cafe_id', $cafe->id)->whereNotIn('id', $table_ids)->delete();
        }

        return $this->show($cafe->id);
    }
}