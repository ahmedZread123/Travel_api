<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class accommodation extends Model
{
    use HasFactory;

    protected $fillable =[
          'name' , 'address' , 'phone' ,
          'email' , 'website' , 'cheack_in_date' ,
          'cheack_in_time' , 'cheack_out_date' ,
          'cheack_out_time' , 'time_zone' , 'trip_id' ,
    ] ;
}
