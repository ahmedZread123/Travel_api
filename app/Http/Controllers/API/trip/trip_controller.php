<?php

namespace App\Http\Controllers\API\trip;

use App\Http\Controllers\Controller;
use App\Models\trip;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class trip_controller extends Controller
{
    /**
     *   table in data = trip - flight - Accommodation - car_Rentel - Restauant - place_visite - transportation - Rail
     *   trip
     *   [id - cauntry - name -  start_date - end_date - public - private - description  ]
     *   flight **
     *      [id - Depart_date - Air_line - flight_number - seat - confirmation  ]
     *      Departure مغادرة  [Airport - Depature Time - Time zone - Dep Terminal - Dep gate ]
     *      Arrival - وصول  [Airport - arrivel date - arrivel time - time zone - arr terminal - arr gate]
     *
     *   Accommodation **
     *       [id - name - cheack_in_date  - cheack_in_time  - time_zone -
     *       cheack_out_date - cheack_out_time - address - phone - email - website ]
     *
     *   car_Rentel **
     *            [id - Rental_Agency - coformation - description ]
     *          plck ip [location_name - address - date - time ]
     *         Drop off [location_name - address - date - time]
     *         details  [car_details - car_type]
     *
     *
     *  Restauant **
     *       [id - name - date - time - time_zone - phone - email - website - coformation]
     *
     *  place_visite **
     *      [id - name - address - date - time - time_zone - phone - email - website]
     *
     *   Rail **
     *       Rail[id  - carrier - coformation -]
     *       Departure[ Dearture_station - address - date - time - time_zone]
     *       arrival [station - address - arrive_date - time - time_zone]
     *       arrival [train_type - train_number - coach - class - seat]
     *  transportation  **
     *       [type- carrier - ]
     *    Departure [location_name - address - date -time - time_Zone]
     *    arrival   [location_name - address - date -time - time_Zone]
     *
     */


    /**
     * fuctions trip
     *   1- create_trip
     *   2- get_trip_people
     *   3- update_trip
     *   4- delete_trip
     *   5- get_trip_my
     *
     */
    use GeneralTrait ;

     // create_trip
        public function create_trip(Request $request)
        {
           try{

            // validate the data
           $validate = validator()->make($request->all(), [
                'country' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'public' => 'boolean',
                'private' => 'boolean',
                'description' => 'string',
                'user_id' => 'required|exists:users,id',
            ]);

            if($validate->fails())
            {
                $code = $this->returnCodeAccordingToInput($validate);
                return $this->returnValidationError($code, $validate);
            }

            // end validate the data

            // check if public or private
                if($request->public == 1 && $request->private == 1)
                {
                    return $this->returnError('you can not set public and private at the same time', 401);
                }
                if($request->public == 0 && $request->private == 0){
                    return $this->returnError('you must set public or private', 401);
                }
             // check if public
                if($request->public == 1)
                {
                    $public = 1;

                }else{
                    $public = 0;
                }
            // check if private

                if($request->private == 1)
                {
                    $private = 1;

                }else{
                    $private = 0;
                }

            // end check if public or private

            // create trip
            $trip = trip::create([
                'country' => $request->country,
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'public' => $public,
                'private' => $private,
                'description' => $request->description,
                'user_id' => $request->user_id,
            ]);

            // end create trip

            // return trip
            if($trip)
            {
                return $this->returnData('trip created successfully', 'trip' , $trip);
            }else{
                return $this->returnError('trip not created', 404);
            }
            // end return trip
           }catch(\Exception $e){
               return $this->returnError( $e->getMessage() , 500);
           }
        }

    // end create_trip

    // get trip people
        public function get_trip_people($id)
        {
            try{
                $user = User::find($id) ;
                if($user){
                   $trip = trip::where('user_id','!=' , $user->id)->get();
                    if($trip)
                    {
                        return $this->returnData('trip people', 'trip' , $trip);
                    }else{
                        return $this->returnError('trip people not found', 404);
                    }
                }else{
                    return $this->returnError('user not found', 404);
                }
            }catch(\Exception $e){
                return $this->returnError( $e->getMessage() , 500);
            }
        }


     // get trip people
     public function get_trip_my($id)
     {
         try{
             $user = User::find($id) ;
             if($user){
               $trip = trip::where('user_id', $user->id)->get();
                 if($trip)
                 {
                     return $this->returnData('trip my', 'trip' , $trip);
                 }else{
                     return $this->returnError('trip my not found', 404);
                 }
             }else{
                 return $this->returnError('user not found', 404);
             }
         }catch(\Exception $e){
             return $this->returnError( $e->getMessage() , 500);
         }
     }

     // update trip



    // delete trip and all  children trip
    public function delete_trip($tripid , $userid)
    {
        try{

            // check if user exist
            $user = User::find($userid) ;
            if($user){
                // check if trip exist
                $trip = trip::find($tripid) ;
                if($trip){
                    // check if trip belong to user
                    if($trip->user_id == $user->id){

                        // delete all children trip
                        // $trip->accommodation()->delete();
                        // $trip->car_rentel()->delete();
                        // $trip->restaurant()->delete();
                        // $trip->place_visite()->delete();
                        // $trip->rail()->delete();
                        // $trip->transportation()->delete();
                        // end delete all children trip

                         // delete trip
                         $trip->delete();
                        return $this->returnData('trip deleted successfully', 'trip' , $trip);
                    }else{
                        return $this->returnError('trip not belong to user', 401);
                    }
                }else{
                    return $this->returnError('trip not found', 404);
                }
            }else{
                return $this->returnError('user not found', 404);
            }

        }catch(\Exception $e){
            return $this->returnError( $e->getMessage() , 500);
        }
    }






}
