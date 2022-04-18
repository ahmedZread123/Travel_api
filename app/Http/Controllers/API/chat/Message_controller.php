<?php

namespace App\Http\Controllers\API\chat;

use App\Http\Controllers\Controller;
use App\Models\message;
use App\Models\room;
use App\Models\user_room;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait ;
use Illuminate\Support\Facades\DB;

class Message_controller extends Controller
{
    use GeneralTrait ;

    /*
      table in data chat  = room  - message  - user_room

      room
      [id  - title - group - photo  - user_id(if group = 1) - date]
      user_room
      [user_id - room_id - date]
      message
      [id - room_id - user_id - message - date]

      function
            1- create message
            2- get message to room
            3- delete message

            5- create  group chat
            6- delete group chat
            7- edit group chat  (title and photo)
            8- leve to group chat
            9- add user to group chat

             // مطلوب
            *- get room singel and group  to user and message && (users if room group)
            *- delete group chat
            *- edit group chat  (title and photo)


    */

    // save message user

    public function save_mesage(Request $request){

        try{


            // valedate
            $valedate = validator()->make($request->all() , [
                'user_id' => 'required|exists:users,id',
                'user_send' => 'required|exists:users,id',
                'room_id' => 'required',
                'message' => 'required' ,
            ], $this->message());

            if($valedate->fails()){
                $code = $this->returnCodeAccordingToInput($valedate);
                return $this->returnValidationError($code, $valedate);
            }

            // check if romm exist
            // save message
             $room = room::find($request->room_id);
             if($room){
                 // check if user is in room
                 $user =   $room->user()->where('user_id' , $request->user_send)->first();
                 if($user){
                    $message = message::create([
                        'user_id'  => $request->user_send ,
                        'room_id'  => $request->room_id ,
                        'message'  => $request->message ,
                        ]);

                        if($message){
                            event(new \App\Events\message_event( $message));
                            return $this->returnData('تم ارسال الرسالة بنجاح ', 'message' , $message);
                        }else{
                            return $this->returnError('حدث خطأ يرجا المحاوله مره اخري', [], 404);
                        }
                 }else{
                     return $this->returnError('user not found in room', 404);
                 }
             }else{
                 DB::beginTransaction() ;
                  $room =  room::create() ;
                   user_room::create([
                          'user_id' => $request->user_id ,
                          'room_id' => $room->id ,
                     ] );

                    user_room::create([
                        'user_id' => $request->user_send,
                        'room_id' => $room->id ,
                   ]);
                        $message = message::create([
                            'user_id'  => $request->user_send ,
                            'room_id'  => $room->id ,
                            'message'  => $request->message ,
                            ]);


                            if($message){
                                DB::commit() ;
                                event(new \App\Events\message_event( $message));
                                return $this->returnData('تم ارسال الرسالة بنجاح ', 'message' , $message);
                            }else{
                                return $this->returnError('حدث خطأ يرجا المحاوله مره اخري', [], 404);
                            }

              }



         }catch(\Exception $ex){
            DB::rollBack() ;
            return $this->returnError($ex->getMessage(),$ex->getCode());

         }


    }

    // end function save message

    // get all message in room

    public function get_message_room($room_id){
      try{
          $room = room::find($room_id);
          if($room){
              $messages = $room->message()->get() ;

              if($messages){
                return $this->returnData('تمت العملية بنجاح ', 'messages' , $messages);

                }else{
                     return $this->returnError('حدث خطأ يرجا المحاوله مره اخري', [], 404);
                   }
            }else{
                   return $this->returnError('حدث خطأ يرجا المحاوله مره اخري', [], 404);
                }

      }catch(\Exception $ex){

            return $this->returnError($ex->getMessage(),$ex->getCode());
        }
    }

    // end get message in room

    // delete message

    public function delete_message($message_id){
        try{
            $message = message::find($message_id);
            if($message){
                $message->delete() ;
                return $this->returnData('تمت العملية بنجاح ', 'message' , $message);
            }else{
                return $this->returnError('حدث خطأ يرجا المحاوله مره اخري', [], 404);
            }
        }catch(\Exception $ex){

            return $this->returnError($ex->getMessage(),$ex->getCode());
        }

    }

    // end delete message

}
