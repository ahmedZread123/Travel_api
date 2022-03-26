<?php

namespace App\Http\Controllers\API\chat;

use App\Http\Controllers\Controller;
use App\Models\room;
use App\Models\user_room;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupChat_Controller extends Controller
{
    use GeneralTrait ;
    // make Group chating

    public function make_group(Request $request){
     try{


        // validator
       $validator =  validator()->make($request->all() , [
         'title'  => 'required' ,
         'photo'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
         'user_id' => 'required|exists:users,id',
       ]) ;


        if($validator->fails()){
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        // end validator

        //save photo
        if($request->has('photo')){
         $photo = $this->saveImage( $request->photo, 'photo_group' ) ;

        }else{
            $photo =null ;
        }

        // save group and make user room to admin
        DB::beginTransaction() ;
         $room_id = room::insertGetId([
         'title'    => $request->title ,
         'photo'    => $photo ,
         'group'    => 1 ,
        ]);


        $room = room::find($room_id) ;
        $user_room = user_room::create([
          'user_id' => $request->user_id ,
          'room_id' => $room ->id,
        ]);
        DB::commit();




        if($room){
            return $this->returnData('تم انشاء مجموعة بنجاح'  , 'room' , $room) ;
        }else{
            return $this->returnError('حدث خطأ يرجا المحاولة لاحقا ' , 500) ;
        }
     }catch(\Exception $ex){
        DB::rollBack() ;
        return $this->returnError($ex->getMessage(),$ex->getCode());

     }

    }

     // add user to group
    public function add_user_to_group(Request $request){
        try{


            // validator
            $validator =  validator()->make($request->all() , [
                'room_id' => 'required|exists:rooms,id',
                'user_id' => 'required|exists:users,id',
            ]) ;


            if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // make new user in group
            $room = room::find($request->room_id) ;

            if($room->group == 1){
                $user_room = user_room::create([
                  'user_id'   => $request->user_id ,
                  'room_id'      => $room->id ,
                ]);
            }

            if($user_room){
                return $this->returnData('تم الاضافة بنجاح ' , 'user_room' , $user_room) ;
            }else{
                return $this->returnError('حدث خطأ يرجا المحاولة لاحقا' , 500) ;
            }


        }catch(\Exception $ex){

            return $this->returnError($ex->getMessage(),$ex->getCode());

         }

    }
     // leave user in group
    public function leave_group(Request $request){

        try{
             // validator
            $validator =  validator()->make($request->all() , [
                'room_id' => 'required|exists:rooms,id',
                'user_id' => 'required|exists:users,id',
            ]) ;


            if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // leve user in group

            $room = room::find($request->room_id) ;
            if($room){
                 $user_room = $room->user()->where('user_id' , $request->user_id) ->first();

               if($user_room){

                 $user_room = user_room::find($user_room->id) ;
                $user_room->delete() ;
                return $this->returnData('تم المغادرة بنجاح ' , 'user_room' , $user_room) ;
               }else{
                   return $this->returnError('حدث خطأ يرجا المحاولة لاحقا ' , 500) ;
               }

            }

        } catch(\Exception $ex){

            return $this->returnError($ex->getMessage(),500);

         }
    }




}
