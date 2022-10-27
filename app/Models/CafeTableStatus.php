<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CafeTableStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'cssClass'
    ];
}
