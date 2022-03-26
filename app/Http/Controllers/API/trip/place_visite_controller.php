<?php

namespace App\Http\Controllers\API\trip;

use App\Http\Controllers\Controller;
use App\Models\place_visite;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class place_visite_controller extends Controller
{
    use GeneralTrait ;
    /**
     * functions
     *  1- create_place_visite
     *  2- get_place_visite
     *  3- update_place_visite
     *  4- delete_place_visite
     */

    // create place_visite
    public function create_place_visite(Request $request)
    {
        try{
             // validate the data
            $validator = validator()->make($request->all(), [
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'time_zone' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'website' => 'required|string|max:255',
                'trip_id' => 'required|exists:trips,id',
            ]);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // end validation

            //create place_visite
            $place_visite = place_visite::create([
                'name' => $request->name,
                'address' => $request->address,
                'date' => $request->date,
                'time' => $request->time,
                'time_zone' => $request->time_zone,
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website,
                'trip_id' => $request->trip_id,
            ]);

            if ($place_visite) {
                return $this->returnData('create place visite', 'place_visite' , $place_visite);
            }else{
                return $this->returnError('not create place visite' , 500) ;
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
