<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'carrier',
        'departure_location_name',
        'departure_address',
        'departure_date',
        'departure_time',
        'departure_time_zone',
        'arrival_location_name',
        'arrival_address',
        'arrival_date',
        'arrival_time',
        'arrival_time_zone',
        'trip_id',

    ];
}
