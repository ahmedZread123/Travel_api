<?php

namespace App\Http\Controllers\API\trip;

use App\Http\Controllers\Controller;
use App\Models\restauant;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait ;
class restouant_controller extends Controller
{
    use GeneralTrait ;
    /**
     * functions
     *  1- create_restauant
     *  2- get_restauant
     *  3- update_restauant
     *  4- delete_restauant
     */

     // create restauant
        public function create_restauant(Request $request)
        {
            try{

            $validator = validator()->make($request->all(), [
                'name' => 'required|string|max:255',
                'cheack_in_date' => 'required|date',
                'cheack_in_time' => 'required|date_format:H:i',
                'time_zone' => 'required|string|max:255',
                'cheack_out_date' => 'required|date',
                'cheack_out_time' => 'required|date_format:H:i|after_or_equal:cheack_in_time',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|string|max:255',
                'website' => 'required|string|max:255',
                'trip_id' => 'required|exists:trips,id',
            ]);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // end validation

            //create restauant
            $restauant = restauant::create([
                'name' => $request->name,
                'cheack_in_date' => $request->cheack_in_date,
                'cheack_in_time' => $request->cheack_in_time,
                'time_zone' => $request->time_zone,
                'cheack_out_date' => $request->cheack_out_date,
                'cheack_out_time' => $request->cheack_out_time,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'trip_id' => $request->trip_id,
            ]);

            if ($restauant) {
                return $this->returnData('restauant created successfully', 'restourant' ,$restauant);
            }else{
                return $this->returnError('something wrong', 500);
            }


        }catch (\Exception $ex){
            return $this->returnError($ex->getMessage(), $ex->getCode());

        }
    }
}
