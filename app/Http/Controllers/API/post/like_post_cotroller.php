<?php

namespace App\Http\Controllers\API\post;

use App\Http\Controllers\Controller;
use App\Models\like;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class like_post_cotroller extends Controller
{

    use  GeneralTrait ;
    // add like to post
    public function add_like(Request $request){
        try{
            //  valedate
            $validate = validator()->make($request->all() , [
                'active' => 'required|in:0,1',
                'post_id' => 'required|exists:posts,id',
                'user_id' => 'required|exists:users,id',
            ],$this->message());

            if($validate->fails()){
                $code = $this->returnCodeAccordingToInput($validate);
                return $this->returnValidationError($code, $validate);
            }

            $like = like::where('user_id' , '=' , $request->user_id)
                        ->where('post_id' , '=' , $request->post_id)
                        ->first() ;
            if($like){
                return $this->returnError(__('message.oredy_like') , []) ;
            }

            DB::beginTransaction() ;
            // save like
            $like = like::create([
                'active' => $request->active,
                'post_id' => $request->post_id,
                'user_id' => $request->user_id,
            ]);
            $user =User::find($request->user_id) ;
            $user->update(['last_seen' => now()]);
            DB::commit() ;
            // return data
            if($like){
                // $like = like::where('id', $like->id)->first();
                return $this->returnData(__('message.s_create_like'), 'like', $like);
            }else{
                return $this->returnError(__('message.r_create_like'),  404);
            }



        }catch(\Exception $ex){
            DB::rollBack() ;
            // return $this->returnError($ex->getCode(),$ex->getMessage());
            return $this->returnError(__('message.error'),404 );

        }

    }

    // delete like from post
    public function delete_like(Request $request , $id){
        try{


            $like = like::find($id);
              if($like){
                  // validate
                    $validate = validator()->make($request->all() , [
                        'user_id' => 'required|exists:users,id' ,
                    ],$this->message());

                    if($validate->fails()){
                        $code = $this->returnCodeAccordingToInput($validate);
                        return $this->returnValidationError($code, $validate);
                    }

                    if($request->user_id == $like->user_id){
                        $like->delete();
                        return $this->returnData(__('message.s_delete_like'), 'like', $like);
                     }else{
                          return $this->returnError(__('message.r_delete_like'),  401);
                    }


                }else{
                    return $this->returnError(__('message.not_like'),  404);
                }
        }catch(\Exception $ex){
            // return $this->returnError($ex->getCode(),$ex->getMessage());
            return $this->returnError(__('message.error'),500 );
        }
    }

}
