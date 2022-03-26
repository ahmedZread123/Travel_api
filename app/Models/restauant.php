<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class restauant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cheack_in_date',
        'cheack_in_time',
        'time_zone',
        'cheack_out_date',
        'cheack_out_time',
        'address',
        'phone',
        'email',
        'website',
        'trip_id',
    ];
}
