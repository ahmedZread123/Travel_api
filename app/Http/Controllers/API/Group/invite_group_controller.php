<?php

namespace App\Http\Controllers\API\Group;

use App\Http\Controllers\Controller;
use App\Models\invite_group;
use App\Models\User;
use App\Models\users_group;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


// use DB ;

class invite_group_controller extends Controller
{
  use GeneralTrait ;
    /*      9 - invite  user to group
            10- accept invite to group
            11- not accept invite to group
    */

    // invite user to group
    public function invite_user(Request $request)
    {
        try{
            $validator = validator()->make($request->all(), [
                'invite_from' => 'required|exists:users,id',
                'invite_to' => 'required|exists:users,id',
                'group_id' => 'required|exists:groups,id',

            ] , $this->message());

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // check if user from  is in group
            $user_group = users_group::where('user_id', $request->invite_from)->where('group_id', $request->group_id)->first();
            if(!$user_group){
                return $this->returnError( __('message.not_user_group'),404);
            }


            // check if user to  is invited to group
            $invite_user = invite_group::where('invite_to', $request->invite_to)->where('group_id', $request->group_id)->first();

            if ($invite_user) {

                return $this->returnError(__('message.already_invited'),[]);
            }

            // check if user to  is in group
            $user_group = users_group::where('user_id', $request->invite_to)->where('group_id', $request->group_id)->first();
            if($user_group){
                return $this->returnError( __('message.user_group'),[]);
            }



           //  create invite
           DB::beginTransaction();
            $invite_user = invite_group::create([
                'invite_from' => $request->invite_from,
                'invite_to' => $request-> invite_to,
                'group_id' => $request-> group_id,
            ]);
            $user = User::find($request->invite_from);
            $user->update(['last_seen' => now()]);
            DB::commit();

            if ($invite_user) {
                return $this->returnData(__('message.invited_group'), 'invite_user',$invite_user);
            }else{
                return $this->returnError( __('message.not_invited_group'),[]);
            }



        } catch (\Exception $ex) {
            DB::rollBack();
            //  return $this->returnError($ex->getMessage() , $ex->getCode());
            return $this->returnError(__('message.error'),500);
        }
    }

    // accept invite to group
    public function accept_invite(Request $request , $id)
    {
        try{
            $validator = validator()->make($request->all(), [
                'user_id' => 'required|exists:users,id',
            ] , $this->message());

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

             $invite_user = invite_group::find($id);
            // return $invite_user-> invite_to;
            if ($invite_user) {
                if($request->user_id == $invite_user->invite_to){


                    DB::beginTransaction();
                    // jone  user to  group
                    $user_group = users_group::create([
                        'user_id' => $invite_user->invite_to,
                        'group_id' => $invite_user->group_id,
                    ]);

                    if ($user_group) {
                        $invite_user->delete();
                        $user  = User::find($invite_user->invite_to);
                        $user->update(['last_seen' => now()]);
                        DB::commit();
                        return $this->returnData(__('message_accept_invited'), 'user_group', $user_group);
                    }
                }else{
                    return $this->returnError( __('message.not_invited_group'),[]);
                }
            }else{
                return $this->returnError( __('message.not_invited_group'),[]);
            }



        } catch (\Exception $ex) {
            DB::rollBack() ;
            return $this->returnError(__('message.error'),500);
        }
    }


    // not accept invite to group
    public function not_accept_invite(Request $request,  $id)
    {

        try{
            //validate
            $validator = validator()->make($request->all(), [
                'user_id' => 'required|exists:users,id',
            ] , $this->message());

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            $invite_user = invite_group::find($id);
            if ($invite_user) {
                if($request->user_id == $invite_user->invite_to){


                    DB::beginTransaction();
                    $invite_user->delete();
                    $user  = User::find($invite_user->invite_to);
                    $user->update(['last_seen' => now()]);
                    DB::commit();

                    return $this->returnSuccessMessage(__('message.not_accept_invited'));
                }else{
                    return $this->returnError( __('message.error'),[]);
                }
            }else{
                return $this->returnError( __('message.not_invited_group'),500);
            }
        } catch (\Exception $ex) {
            DB::rollBack() ;
            return $this->returnError(__('message.error'),500);
        }
    }






}
