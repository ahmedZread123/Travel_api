<?php

namespace App\Http\Controllers\API\chat;

use App\Http\Controllers\Controller;
use App\Models\frend;
use App\Models\room;
use App\Models\User;
use App\Models\user_room;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait ;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Socket;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Translation\Util\ArrayConverter;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTAuth as JWTAuthJWTAuth;

class frend_controller extends Controller
{
    public $data   ;

    use GeneralTrait ;

    public function frend($id)
    {
        try{
        // get all frends

        $user = User::find($id);
        // get frends of user
         $frend_in= $user->frends()->where('is_accepted', 1) ->orderby('created_at', 'desc')->get();
         $frend_to = $user->frends_to()->where('is_accepted', 1) ->orderby('created_at', 'desc')->get();
         $frends = $frend_in->merge($frend_to);
        // get frend
       foreach ($frends as $key => $frend) {
          $user_room = user_room::where('user_id', $user->id )
                          ->orwhere('user_id' ,$frend->frend_id)
                          ->orwhere('user_id' ,$frend->user_id)
                          ->get() ;

         $frends[$key]->room_id= $user_room->mode('room_id') ;

       }

       // get group
        $user_rooms   =   $user->user_room()->get() ;
        if(!$user_rooms){
            $Group = null ;
        }
            foreach($user_rooms as $user_room){
                $room = room::find($user_room->room_id) ;
                $Group = $room->where('group' , 1) ->get() ;
            }


        // get onlin frends
        foreach($frends as $key => $frend){
            if($frend->last_seen >= Carbon::now()->subMinutes(3)){
                $frends[$key]->online = true ;
            }else{
                $frends[$key]->online = false ;
                $frends[$key]-> last_seen = Carbon::parse($user->last_seen)->diffForHumans() ;

            }

        }





        // return data

        return response()->json([
            'status' => true,
            'errNum' => "",
            'msg' => 'جميع الاصدقاء و المجموعات ',
            'frends' => $frends,
            // 'group' => $Group ,

        ]);

        }catch(\Exception $ex){
            return $this->returnError($ex->getMessage(),500);

        }
    }



     // friend request send to user
    public function friend_request(Request $request){

          try{
            $valedate = validator()->make($request->all() , [
                'user_id' => 'required|exists:users,id',
                'frend_id' => 'required|exists:users,id',
            ]);

            if($valedate->fails()){
                $code = $this->returnCodeAccordingToInput($valedate);
                return $this->returnValidationError($code, $valedate);
            }

            // check if user is frend with frends
             $frend = frend::where('user_id', $request->user_id)
                          ->where('frend_id', $request->frend_id)
                          ->first();
            $frend_to = frend::where('user_id', $request->frend_id)
                                ->where('frend_id',$request->user_id)
                                ->first();
            if($frend || $frend_to){
                return $this->returnError('    هذا المستخدم بالقعل صديقك ', [], 403);
            }

            DB::beginTransaction();
            // save frend
            $frend = frend::create([
                'user_id' => $request->user_id,
                'frend_id' => $request->frend_id,
            ]);


            DB::commit();


                return $this->returnData('تم ارسال طلب الصداقة بنجاح ', 'frend' , $frend);


          }catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError($ex->getMessage(),$ex->getCode());
        }
    }

    // friend request get from user
    public function frind_request_get($id){

            try{
                $user = User::find($id);
                if(!$user){
                    return $this->returnError('user not found', [], 404);
                }
                $frends = $user->frends()->where('is_accepted', 0)->get();
               if($frends){
                // return data
                return $this->returnData('جميع الاصدقاء ', 'frends' , $frends);
               }else{
                return $this->returnError('لا يوجد طلبات صداقة', [], 404);
               }

            }catch(\Exception $ex){
                return $this->returnError($ex->getCode(),$ex->getMessage());

            }
    }


    // accept friend request
    public function accept_friend_request($id){
        try{
              DB::beginTransaction() ;
             $frend = frend::find($id);
             if($frend){
                $frend->is_accepted = 1;
                $frend->save();

                  // return data

                $frend = frend::where('id', $frend->id)->first();

                // create new room
                    $room =  room::create();
                if($room){

                // crteate new   user_room  to user
                user_room::create([
                    'user_id' => $frend->user_id,
                    'room_id' => $room->id,
                ]);
                // crteate new user_room  to frend
                user_room::create([
                    'user_id' => $frend->frend_id,
                    'room_id' => $room->id,
                ]);

                DB::commit() ;

             }
             // end create room and user_room
                return $this->returnData('تم قبول طلب الصداقة بنجاح ', 'frend' , $frend);
            }else{
                return $this->returnError('حدث خطأ يرجا المحاوله مره اخري', [], 404);
            }

        }catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }

    // delete friend request
    public function delete_friend_request($id){
        try{


             $frend = frend::where('is_accepted', 0)->where('id', $id)->first();

             if($frend){
                $frend->delete();
                return $this->returnData('تم حذف طلب الصداقة بنجاح ', 'frend' , $frend);
             }else{
                return $this->returnError('حدث خطأ يرجا المحاوله مره اخري', [], 404);
             }



        }catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }



     // online user
    public function onlin_users(){

        $users = User::all();
        // foreach ($users as $user) {
        //     if (Cache::has($user->id))
        //         echo $user->id . " is online. Last seen: " . Carbon::parse($user->last_seen)->diffForHumans() . " <br>";
        //     else
        //         echo $user->id . " is offline. Last seen: " . Carbon::parse($user->last_seen)->diffForHumans() . " <br>";
        // }

        foreach ($users as $user) {
               if($user->last_seen >= Carbon::now()->subMinutes(3)){
                echo $user->id . " is online";
               }else{
                echo $user->id . " is offline. Last seen: " . Carbon::parse($user->last_seen)->diffForHumans() ;
               }
        }

    }





}
