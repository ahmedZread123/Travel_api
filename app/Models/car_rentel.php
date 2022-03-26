<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class car_rentel extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_agency',
        'coformation',
        'description',
        'pick_up_location_name',
        'pick_up_address',
        'pick_up_date',
        'pick_up_time',
        'drop_off_location_name',
        'drop_off_address',
        'drop_off_date',
        'drop_off_time',
        'car_details',
        'car_type',
        'trip_id'
    ];

}
