<?php

namespace App\Http\Controllers\API\post;

use App\Http\Controllers\Controller;
use App\Models\save_psot;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class save_post_controller extends Controller
{
    // save post
   use GeneralTrait ;
    public function save_post(Request $request)
    {
        try{


            # validate
            $validate = validator()->make($request->all() , [
                'post_id' => 'required|exists:posts,id',
                'user_id' => 'required|exists:users,id',
            ],$this->message());

            if($validate->fails()){
                $code = $this->returnCodeAccordingToInput($validate);
                return $this->returnValidationError($code, $validate);
            }
             // check if user saved this post before
            $user = User::find($request->user_id);

            if ($user->save_post()->where('post_id' , $request->post_id)->exists()) {
                return $this->returnError(__('message.already_save'), [], 404);
            }

            # save save_post
            DB::beginTransaction() ;
            $save_post = save_psot::create([
                'post_id' => $request->post_id,
                'user_id' => $request->user_id,
            ]);
            $user->update(['last_seen' => now()]);
            DB::commit() ;

            # return data
            if($save_post){

                return $this->returnData(__('message.s_create_save'), 'save_post', $save_post);
            }else{
                return $this->returnError(__('message.r_create_save'), [], 404);
            }
        }catch(\Exception $ex){
            DB::rollBack() ;
            // return $this->returnError($ex->getCode(),$ex->getMessage());
            return $this->returnError(__('message.error'), 500 );

        }

    }

    // delete save post
    public function unsave_post(Request $request , $id){
        try{

            $save_post = save_psot::find($id);
            if($save_post){
                // validate
                $validate = validator()->make($request->all() , [
                    'user_id' => 'required|exists:users,id',

                ],$this->message());

                if($validate->fails()){
                    $code = $this->returnCodeAccordingToInput($validate);
                    return $this->returnValidationError($code, $validate);
                }

                // check if user saved this post before
                $user = User::find($request->user_id);



                // check if user successfuly
                if($user->id != $save_post->user_id){

                  return $this->returnError(__('message.not_user_save'), 403);

                }

                if (!$user->save_post()->where('post_id' , $save_post->post_id)->exists()) {
                    return $this->returnError(__('message.not_save'),  404);
                }


                // delete save_post
                DB::beginTransaction() ;
                $save_post->delete();
                $user->update(['last_seen' => now()]);
                DB::commit() ;

                // return data
                if($save_post){
                    return $this->returnData(__('message.s_delete_save'), 'save_post', $save_post);
                }else{
                    return $this->returnError(__('message.r_delete_save'),  404);
                }
            }else{
                return $this->returnError(__('message.not_save'),  404);

            }

        }catch(\Exception $ex){
            DB::rollBack() ;
            // return $this->returnError($ex->getCode(),$ex->getMessage());
            return $this->returnError(__('message.error'), 500 );

        }

    }
}
