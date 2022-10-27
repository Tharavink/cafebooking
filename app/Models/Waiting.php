<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waiting extends Model
{
    use HasFactory;
    protected $fillable = [
        'cafe_id', 'user_id', 'number'
    ];

    public function cafe()
    {
        return $this->belongsTo('App\Models\Cafe', 'cafe_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
