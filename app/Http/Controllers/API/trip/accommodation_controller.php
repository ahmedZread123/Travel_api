<?php

namespace App\Http\Controllers\API\trip;

use App\Http\Controllers\Controller;
use App\Models\accommodation;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class accommodation_controller extends Controller
{
    use GeneralTrait ;
    /**
     * functions accommodation
     * 1- create_accommodation
     * 2- get_accommodation_trip
     * 3- update_accommodation
     * 4- delete_accommodation
     *
     *
     *   'name' , 'address' , 'phone' ,
       *   'email' , 'website' , 'cheack_in_date' ,
      *    'cheack_in_time' , 'cheack_out_date' ,
       *   'cheack_out_time' , 'time_zone' , 'trip_id' ,
     */

    // create accommodation
    public function create_accommodation(Request $request)
    {
        try {
            // validator
            $validator = validator()->make($request->all(), [
                'name'            => 'required|string|max:255',
                'address'         => 'required|string|max:255',
                'phone'           => 'required|string',
                'trip_id'         => 'required|exists:trips,id',
                'cheack_in_date'  => 'required|date',
                'cheack_out_date' => 'required|date',
                'cheack_in_time'  => 'required|date_format:H:i' ,
                'cheack_out_time' => 'required|date_format:H:i|after_or_equal:cheack_in_time' ,
                'time_zone'       => 'required|string' ,
                'email'           => 'required|email' ,
                'website'         => 'required|string' ,
            ],$this->message);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // create
            $acco  = accommodation::create([
                'name'            => $request->name,
                'address'         => $request->address,
                'phone'           => $request->phone,
                'trip_id'         => $request->trip_id,
                'cheack_in_date'  => $request->cheack_in_date ,
                'cheack_out_date' => $request->cheack_out_date,
                'cheack_in_time'  => $request->cheack_in_time,
                'cheack_out_time' => $request->cheack_out_time,
                'time_zone'       => $request->time_zone,
                'email'           => $request-> email,
                'website'         => $request->website,
            ]);

            if($acco){
                return $this->returnData('create accommodation' , 'accommodation' , $acco) ;
            }else{
                return $this->returnError('not create new accommodation' ,  500) ;
            }

        } catch (\Exception $ex) {
            return $this->returnError($ex->getMessage(), $ex->getCode());
        }
    }






}
