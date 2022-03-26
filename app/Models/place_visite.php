<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class place_visite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'date',
        'time',
        'time_zone',
        'phone',
        'email',
        'website',
        'trip_id',
    ];
}
