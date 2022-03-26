<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class flight extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     *  *      [id - Depart_date - Air_line - flight_number - seat - confirmation  ]
     *     Departure مغادرة  [Airport - Depature Time - Time zone - Dep Terminal - Dep gate ]
     *      Arrival - وصول  [Airport - arrivel date - arrivel time - time zone - arr terminal - arr gate]
     *
     */

    protected $fillable = [
      'Depart_date','Air_line' , 'flight_number' , 'seat' , 'confirmation' ,
      'Depart_Airport' , 'Depart_Time' , 'Depart_Time_Zone' , 'Depart_Terminal' , 'Depart_Gate' ,
      'Arrive_Airport' , 'Arrive_Date' , 'Arrive_Time' , 'Arrive_Time_Zone' , 'Arrive_Terminal' , 'Arrive_Gate' ,
      'trip_id' ,

    ];
}
