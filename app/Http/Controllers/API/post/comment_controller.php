<?php

namespace App\Http\Controllers\API\post;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class comment_controller extends Controller
{
    use GeneralTrait;


     // save comment
    public function store(Request $request)
    {
        try{
            //  valedate
            $validate = validator()->make($request->all() , [
                'comment' => 'required',
                'post_id' => 'required|exists:posts,id',
                'user_id' => 'required|exists:users,id',

            ],$this->message());
            if($validate->fails()){
                $code = $this->returnCodeAccordingToInput($validate);
                return $this->returnValidationError($code, $validate);
            }

            // save comment
            DB::beginTransaction() ;
            $comment = comment::create([
                'comment' => $request->comment,
                'post_id' => $request->post_id,
                'user_id' => $request->user_id,
            ]);
            $user =User::find($request->user_id) ;
            $user->update(['last_seen' => now()]);
            DB::commit() ;
            // return data
            if($comment){
                $comment = comment::where('id', $comment->id)->first();
                return $this->returnData(__('message.s_create_comment'), 'comment', $comment);
            }else{
                return $this->returnError(__('message.r_create_comment'), [], 404);
            }


        }catch(\Exception $ex){
            DB::rollBack() ;
            // return $this->returnError($ex->getCode(),$ex->getMessage());
            return $this->returnError(__('message.error'), [] );

        }
    }


    public function show($id)
    {
        try{
           $comment = comment::find($id);
           if($comment){
               return $this->returnData('comment found successfully', 'comment', $comment);
              }else{
                return $this->returnError('comment not found', [], 404);
            }
        }catch(\Exception $ex){
            // return $this->returnError($ex->getCode(),$ex->getMessage());
            return $this->returnError("حدث خطأ يرجا المحاوله مره اخري", [] );

        }
    }



    public function update(Request $request, $id)
    {
        try{

            //  valedate
            $comment = comment::find($id);
            if($comment){
            $validate = validator()->make($request->all() , [
                'comment' => 'required',
                'post_id' => 'required|exists:posts,id',
                'user_id' => 'required|exists:users,id',

                ],$this->message());
                if($validate->fails()){
                    $code = $this->returnCodeAccordingToInput($validate);
                    return $this->returnValidationError($code, $validate);
            }

            // check if user == comment
              $coom =  $comment->where('user_id' , '=' ,  $request->user_id )->first() ;
            if(!$coom ){
              return $this->returnError(__('message.user_not_comment') , 404) ;

            }
                // update  comment
                DB::beginTransaction();
                $comment ->update([
                    'comment' => $request->comment,
                    'post_id' => $request->post_id,
                    'user_id' => $request->user_id,
                ]);
                $user =User::find($request->user_id) ;
                $user->update(['last_seen' => now()]);
                DB::commit() ;
                // return data
                if($comment){
                    $comment = comment::where('id', $comment->id)->first();
                    return $this->returnData(__('message.s_update_comment'), 'comment', $comment);
                }else{
                    return $this->returnError(__('message.r_update_comment'), [], 404);
                }
                }else{
                    return $this->returnError(__('message.not comment'), [], 404);
                }



        }catch(\Exception $ex){
            DB::rollBack() ;
            // return $this->returnError($ex->getCode(),$ex->getMessage());
            return $this->returnError(__('message.error'), [] );

        }
    }


    public function destroy($id)
    {
        try{
            $comment = comment::find($id);

            if($comment){
                DB::beginTransaction();

                $comment->delete();
                DB::commit();
                return $this->returnData(__('message.s_delete_comment'), 'comment', $comment);
            }else{
                return $this->returnError(__('message.r_delete_comment'), [], 500);
            }
        }catch(\Exception $e){
            DB::rollBack();
            // return $this->returnError($e->getMessage(), [], 500);
            return $this->returnError(__('message.error'), [] );

        }
    }

}



