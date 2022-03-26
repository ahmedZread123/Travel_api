<?php

namespace App\Http\Controllers\API\trip;

use App\Http\Controllers\Controller;
use App\Models\flight;
use Illuminate\Http\Request;
use App\Models\trip;
use App\Traits\GeneralTrait ;
class flight_controller extends Controller
{
    use GeneralTrait ;
    /**
     * functions flight
     *  1- create_flight
     *  2- get_flight_trip
     *  3- update_flight
     *  4- delete_flight
     *
     */

     // create flight
        public function create_flight(Request $request)
        {
            try {
                // validator
                $validator = validator()->make($request->all(), [
                    'trip_id' => 'required|exists:trips,id',
                    'Depart_date' => 'required|date',
                    'Air_line' => 'required|string',
                    'flight_number' => 'required|string',
                    'seat' => 'required|string',
                    'confirmation' => 'required|string',
                    'Depart_Airport' => 'required|string',
                    'Depart_Time' => 'required|date_format:H:i',
                    'Depart_Time_Zone' => 'required|string',
                    'Depart_Terminal' => 'required|string',
                    'Depart_Gate' => 'required|string',
                    'Arrive_Airport' => 'required|string',
                    'Arrive_Date' => 'required|date',
                    'Arrive_Time' => 'required|date_format:H:i',
                    'Arrive_Time_Zone' => 'required|string',
                    'Arrive_Terminal' => 'required|string',
                    'Arrive_Gate' => 'required|string',
                ]);
                if ($validator->fails()) {
                    $code = $this->returnCodeAccordingToInput($validator);
                    return $this->returnValidationError($code, $validator);
                }
                 // create flight
                $flight = flight::create([
                    'trip_id' => $request->trip_id,
                    'Depart_date' => $request->Depart_date,
                    'Air_line' => $request->Air_line,
                    'flight_number' => $request->flight_number,
                    'seat' => $request->seat,
                    'confirmation' => $request->confirmation,
                    'Depart_Airport' => $request->Depart_Airport,
                    'Depart_Time' => $request->Depart_Time,
                    'Depart_Time_Zone' => $request->Depart_Time_Zone,
                    'Depart_Terminal' => $request->Depart_Terminal,
                    'Depart_Gate' => $request->Depart_Gate,
                    'Arrive_Airport' => $request->Arrive_Airport,
                    'Arrive_Date' => $request->Arrive_Date,
                    'Arrive_Time' => $request->Arrive_Time,
                    'Arrive_Time_Zone' => $request->Arrive_Time_Zone,
                    'Arrive_Terminal' => $request->Arrive_Terminal,
                    'Arrive_Gate' => $request->Arrive_Gate,
                ]);

                if ($flight) {
                    return $this->returnData('flight created successfully', 'flight' , $flight);
                } else {
                    return $this->returnError('flight not created', 404);
                }

            } catch (\Exception $ex) {
                return $this->returnError($ex->getMessage(), $ex->getCode());
            }
        }

        // get flight trip
        public function get_flight_trip($trip_id)
        {
            try {
                $flight = flight::where('trip_id', $trip_id)->get();
                if ($flight) {
                    return $this->returnData('flight trip', 'flight' , $flight);
                } else {
                    return $this->returnError('flight trip not found', 404);
                }
            } catch (\Exception $ex) {
                return $this->returnError($ex->getMessage(), $ex->getCode());
            }
        }

}
