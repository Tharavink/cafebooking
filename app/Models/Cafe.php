<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'logo_id', 'owner_id'
    ];
    protected $appends = ['logo'];

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'owner_id');
    }

    public function tables()
    {
        return $this->hasMany('App\Models\CafeTable');
    }

    public function bookings()
    {
        return $this->hasMany('App\Models\Booking');
    }

    public function logo_file()
    {
        return $this->belongsTo('App\Models\File', 'logo_id');
    }

    public function getLogoAttribute()
    {
        if ($this->logo_file) {
            return $this->logo_file->file;
        } else {
            return null;
        }
    }
}
