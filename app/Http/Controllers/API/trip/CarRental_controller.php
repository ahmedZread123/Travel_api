<?php

namespace App\Http\Controllers\API\trip;

use App\Http\Controllers\Controller;
use App\Models\car_rentel;
use App\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class CarRental_controller extends Controller
{
    use GeneralTrait ;
    /**
     * functions
     *   1- create_car_rental
     *   2- get_car_rental
     *   3- update_car_rental
     *   4- delete_car_rental
     *
     */


      // create car rental
        public function create_car_rental(Request $request)
        {
           try{

            // validation
            $validator =  validator()->make($request->all(), [
                'rental_agency' => 'required|string|max:255',
                'coformation' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'pick_up_location_name' => 'required|string|max:255',
                'pick_up_address' => 'required|string|max:255',
                'pick_up_date' => 'required|date',
                'pick_up_time' => 'required|date_format:H:i',
                'drop_off_location_name' => 'required|string|max:255',
                'drop_off_address' => 'required|string|max:255 ',
                'drop_off_date' => 'required|date',
                'drop_off_time' => 'required|date_format:H:i',
                'car_details' => 'required|string|max:255',
                'car_type' => 'required|string|max:255',
                'trip_id' => 'required|exists:trips,id',
            ]);

            if($validator->fails())
            {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // end validation

            //create car rental
            $car_rental = car_rentel::create([
                'rental_agency' => $request->rental_agency,
                'coformation' => $request->coformation,
                'description' => $request->description,
                'pick_up_location_name' => $request->pick_up_location_name,
                'pick_up_address' => $request->pick_up_address,
                'pick_up_date' => $request->pick_up_date,
                'pick_up_time' => $request->pick_up_time,
                'drop_off_location_name' => $request->drop_off_location_name,
                'drop_off_address' => $request->drop_off_address,
                'drop_off_date' => $request->drop_off_date,
                'drop_off_time' => $request->drop_off_time,
                'car_details' => $request->car_details,
                'car_type' => $request->car_type,
                'trip_id' => $request->trip_id,
            ]);

            // end create car rental

            // return response data
            if($car_rental)
            {
                return $this->returnData('car rental created successfully','cre-rental' ,  $car_rental);
            }else{
                return $this->returnError('car rental not created', 404);
            }


           }catch(\Exception $e){
             return $this->returnError($e->getCode(), $e->getMessage());
           }
        }


}
