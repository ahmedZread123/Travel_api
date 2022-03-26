<?php

namespace App\Http\Controllers\API\chat;

use App\Http\Controllers\Controller;
use App\Models\follow;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class follow_controller extends Controller
{
    use GeneralTrait ;

    /**
     *      follow users
     *      table : follow_users
     *      fowllo_users
     *      [id - user_id , follow_to - active](check if user private)
     *
     *      function
     *
     *      1- follow to
     *      2- accept follow if user privet
     *      3 - not accept follow usre privet
     *      4 - get following and followers in user profile
     *
     *
     */


     // follow to user
    public function follow_to(Request $request)
    {
        try {
            // validator
            $validator = validator()->make($request->all(), [
                'user_id'   => 'required|exists:users,id',
                'follow_to' => 'required|exists:users,id',
            ] , $this->message());
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $user = User::find($request->user_id);
            DB::beginTransaction();
            $user->update(['last_seen' => now()]);


            // check if user follow to
            $follow = follow::where('user_id', $request->user_id)->where('follow_to', $request->follow_to)->first();
            if ($follow) {
                return $this->returnError(__('messagechat.already_follow'), []);
            }
            // check if user private and save follow to

            if ($user->private == 1) {
                $follow = follow::create([
                    'user_id'   => $request->user_id,
                    'follow_to' => $request->follow_to,
                    'active'    => 0,
                   ]);
                   DB::commit() ;
                if ($follow) {

                return $this->returnData(__('messagechat.request_follow_success'), 'follow' , $follow);
                }else{
                    return $this->returnError(__('messagechat.request_follow_error'), []);
                }

            }else{
                    // save follow to user if not private
                    $follow = follow::create([
                        'user_id'   => $request->user_id,
                        'follow_to' => $request->follow_to,
                        'active'    => 1,
                    ]);
                    DB::commit() ;
                    if($follow){
                        return $this->returnData(__('messagechat.follow_success'), 'follow' , $follow);

                    }else{
                        return $this->returnError(__('messagechat.follow_error'), []);
                    }
            }





        } catch (\Exception $ex) {
            DB::rollBack();
            // return $this->returnError($ex->getMessage(), $ex->getCode());
            return $this->returnError(__('message.error'),500);
        }
    }


    // accept follow if user privet
    public function accept_follow(Request $request , $id)
    {
        try {

            // validator
            $validator = validator()->make($request->all(), [
                'user_id'   => 'required|exists:users,id',

            ] , $this->message());
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            $follow = follow::find($id);
            if($request->user_id == $follow->user_id){
                // check follow
                if (!$follow) {
                    return $this->returnError(__('messagechat.not_found_follow'), []);
                }
                // check follow active == 1
                if($follow->active == 1){
                    return $this->returnError(__('messagechat.already_accept_follow'), []);
                }
                DB::beginTransaction();
                // save follow to user
                $follow->update([
                'active' => 1,
                ]);
                $user = User::find($follow->user_id);
                $user->update(['last_seen' => now()]);

                DB::commit() ;
                if($follow){
                    return $this->returnSuccessMessage(__('messagechat.follow_accept_success'), []);
                 }else{
                    return $this->returnError(__('messagechat.follow_accept_error'), []);
                }

            }else{
                return $this->returnError(__('messagechat.not_follow_you'), 404);
            }

        } catch (\Exception $ex) {
            DB::rollBack() ;
            // return $this->returnError($ex->getMessage(), $ex->getCode());
            return $this->returnError(__('message.error'),500);
        }
    }


    // not accept follow if user privet
    public function not_accept_follow(Request $request , $id)
    {
        try {

            // validator
            $validator = validator()->make($request->all(), [
                'user_id'   => 'required|exists:users,id',

            ] , $this->message());
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $follow = follow::find($id);
            if (!$follow) {
                return $this->returnError(__('messagechat.not_found_follow'), []);
            }
            $user = User::find($request->user_id);


            if($user->id == $follow->user_id){
                // check follow

                // check follow active == 1
                if($follow->active == 1){
                    return $this->returnError(__('messagechat.already_accept_follow'), []);
                }

                // save follow to user
                DB::beginTransaction() ;
                $follow->delete();
                $user->update(['last_seen' => now()]);
                DB::commit() ;
                if($follow){
                    return $this->returnSuccessMessage(__('messagechat.follow_not_accept_success'), []);
                 }else{
                    return $this->returnError(__('messagechat.follow_not_accept_error'), []);
                }
            }else{
                return $this->returnError(__('messagechat.not_follow_you'), 404);
            }

        } catch (\Exception $ex) {
            DB::rollBack() ;
            // return $this->returnError($ex->getMessage(), $ex->getCode());
            return $this->returnError(__('message.error'),500);
        }
    }

    public function get_follow($id){
        try {
           $user = User::find($id);
           $user ->update(['last_seen' => now()]);
              if(!$user){
                return $this->returnError(__('message.user_ont_found') , 404);
              }
               $user->followers = $user->followers()->where('active', 1)->get();
                $user->following = $user->following()->where('active', 1)->get();
                if($user){
                   return $this->returnData('تم اسال البيانات بنجاح ', 'follow' , $user);

                }else{
                    return $this->returnError(__('message.error'), []);
                }
        } catch (\Exception $ex) {

            return $this->returnError(__('message.error'), []);

        }
    }






}
