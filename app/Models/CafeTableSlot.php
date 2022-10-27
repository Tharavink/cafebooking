<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CafeTableSlot extends Model
{
    use HasFactory;
    protected $fillable = [
        'table_id', 'number', 'disallowed'
    ];

    public function cafe_table()
    {
        return $this->belongsTo('App\Models\CafeTable', 'table_id');
    }

    public function bookings()
    {
        return $this->hasMany('App\Models\Booking', 'slot_id');
    }
}
