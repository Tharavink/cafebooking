<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CafeTable extends Model
{
    use HasFactory;
    protected $fillable = [
        'cafe_id', 'number', 'status_id'
    ];

    public function cafe()
    {
        return $this->belongsTo('App\Models\Cafe', 'cafe_id');
    }

    public function slots()
    {
        return $this->hasMany('App\Models\CafeTableSlot', 'table_id');
    }

    public function bookings()
    {
        return $this->hasMany('App\Models\Booking', 'table_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\CafeTableStatus', 'status_id');
    }
}
