<?php

namespace App\Http\Controllers\API\trip;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\transportation;
use App\Traits\GeneralTrait;
class transportation_controller extends Controller
{
    use GeneralTrait ;
    /**
     * functions
     *  1- create_transportation
     *  2- get_transportation
     *  3- update_transportation
     *  4- delete_transportation
     */

    // create transportation
    public function create_transportation(Request $request)
    {
        try{
             // validate the data
            $validator = validator()->make($request->all(), [
                'type' => 'required|string|max:255',
                'carrier' => 'required|string|max:255',
                'departure_location_name' => 'required|string|max:255',
                'departure_address' => 'required|string|max:255',
                'departure_date' => 'required|date',
                'departure_time' => 'required|date_format:H:i',
                'departure_time_zone' => 'required|string|max:255',
                'arrival_location_name' => 'required|string|max:255',
                'arrival_address' => 'required|string|max:255',
                'arrival_date' => 'required|date|after_or_equal:departure_date',
                'arrival_time' => 'required|date_format:H:i|after:departure_time',
                'arrival_time_zone' => 'required|string|max:255',
                'trip_id' => 'required|exists:trips,id',
            ]);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // end validation

            //create transportation
            $transportation = transportation::create([
                'type' => $request->type,
                'carrier' => $request->carrier,
                'departure_location_name' => $request->departure_location_name,
                'departure_address' => $request->departure_address,
                'departure_date' => $request->departure_date,
                'departure_time' => $request->departure_time ,
                'departure_time_zone' => $request->departure_time_zone,
                'arrival_location_name' => $request->arrival_location_name,
                'arrival_address' => $request->arrival_address,
                'arrival_date' => $request->arrival_date,
                'arrival_time' => $request->arrival_time,
                'arrival_time_zone' => $request->arrival_time_zone,
                'trip_id' => $request->trip_id,
            ]);
            // end create transportation

            // return transportation
            if ($transportation) {
                return $this->returnData('create transportation', 'transportation' , $transportation);
            }else{
                return $this->returnError('not create transportation' , 500) ;
            }
        }catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
