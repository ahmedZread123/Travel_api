<?php

namespace App\Http\Controllers\API\post;

use App\Http\Controllers\Controller;
use App\Models\post;
use App\Models\share_post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait ;
use Illuminate\Support\Facades\DB;

class share_post_controller extends Controller
{
    use GeneralTrait ;
    // save share post
    public function save_share_post(Request $request)
    {
        try{
            //validator
            $validator = validator()->make($request->all() , [
                'post_id' => 'required|exists:posts,id',
                'user_id' => 'required|exists:users,id',
            ],$this->message());

            if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError( $code, $validator);
            }

            //check if post exist
            $post = post::where('id', $request->post_id)->first();
            if($post){
                //check if user is owner of post
                if($post->user_id != $request->user_id){
                    //check if user already share this post
                    if(!share_post::where('post_id', $request->post_id)->where('user_id', $request->user_id)->exists()){
                        DB::beginTransaction();
                        $share_post = share_post::create([
                            'user_id' => $request->user_id,
                            'post_id' => $request->post_id,
                        ]);
                        $user = User::find($request->user_id);
                        $user->update(['last_seen' => now()]);
                        DB::commit() ;
                        if($share_post){
                            return $this->returnData(__('message.s_create_share'), 'share_post' , $share_post);
                        }else{
                            return $this->returnError(__('message.r_create_share'),  404);
                        }

                    }else{
                        return $this->returnError(__('message.already_share'), 401);
                    }
                }else{
                    return $this->returnError(__('message.r_create_share'),  404);
                }

            }else{
                return $this->returnError(__('message.not_post'),  404);
            }
            // end check if post exist

        }catch(\Exception $ex){
            DB::rollBack() ;
            return $this->returnError(__('message.error'), 500 );
        }
    }

    // unsave share post
    public function unsave_share_post(Request $request)
    {
        try{
            //validator
            $validator = validator()->make($request->all() , [
                'post_id' => 'required|exists:posts,id',
                'user_id' => 'required|exists:users,id',
            ],$this->message());
            if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError( $code, $validator);
            }
            //check if post exist
            $post = post::where('id', $request->post_id)->first();
            if($post){
                //check if user is owner of post
                if($post->user_id != $request->user_id){
                    //check if user already share this post
                    if(share_post::where('post_id', $request->post_id)->where('user_id', $request->user_id)->exists()){

                        $share_post = share_post::where('post_id', $request->post_id)->where('user_id', $request->user_id)->first();
                        DB::beginTransaction() ;
                        $share_post->delete();
                        $user = User::find($request->user_id) ;
                        $user->update(['last_seen' => now()]);
                        DB::commit() ;
                        if($share_post){
                            return $this->returnData(__('message.s_delete_share'), 'share_post' , $share_post);
                        }else{
                            return $this->returnError(__('message.r_delete_share'),  404);
                        }

                    }else{
                        return $this->returnError(__('message.not_share'),  404);
                    }
                }else{
                    return $this->returnError(__('message.not_share'),  404);
                }

            }else{
                return $this->returnError(__('message.not_post'), 404);
            }
            // end check if post exist

        }catch(\Exception $ex){
            DB::rollBack() ;
            return $this->returnError(__('message.error'), 500 );
        }
    }
}
