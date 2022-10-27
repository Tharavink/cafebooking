<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'table_id', 'cafe_id', 'sign_in', 'sign_out', 'number', 'status_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function cafe_table()
    {
        return $this->belongsTo('App\Models\CafeTable', 'table_id');
    }

    public function cafe()
    {
        return $this->belongsTo('App\Models\Cafe', 'cafe_id');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\BookingStatus', 'status_id');
    }
}
