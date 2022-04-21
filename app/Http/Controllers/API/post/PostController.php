<?php

namespace App\Http\Controllers\API\post;

use App\Http\Controllers\Controller;
use App\Models\comment;
use App\Models\group;
use App\Models\like;
use Illuminate\Http\Request;
use App\Models\post ;
use App\Models\save_psot;
use App\Models\share_post;
use App\Models\User;
use App\Models\users_group;
use App\Traits\GeneralTrait;
use GuzzleHttp\Psr7\UploadedFile;
use Hamcrest\Arrays\IsArray;
use Hamcrest\Type\IsArray as TypeIsArray;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    use GeneralTrait ;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


        /*
            table in post = post - comment - like - share - save_post

            post
            [id  - user_id - title - describtion - photo[] - date]
            comment
            [id - post_id - user_id - comment - date]
            like
            [id - post_id - user_id - date]
            share
            [id - post_id - user_id - date]
            save_post
            [id - post_id - user_id - date]



            function
                1- create post
                2- delete post
                3- edit post
                4- get post with user and comment and like and share and save_post
                5- shwo one psot with user and comment and like and share and save_post

                5- save comment
                6- delete comment
                7- save like
                8- delete like
                9- save share
                10- delete share
                11- save save-post
                12- delete save-post


        */



    public function index()
    {
        //
        try{
             $posts = post::where('only', 0)
             ->with('user' , 'comments' , 'likes' , 'shares' , 'saves')
            -> get();


            if($posts != []){
                // foreach($posts as $post){
                //     $post->comments = comment::where('post_id', $post->id)->get() ;
                //     $post->likes = like::where('post_id', $post->id)->get() ;
                //     $post->saves = save_psot::where('post_id', $post->id)->get() ;
                //     $post->shares = share_post::where('post_id', $post->id)->get() ;
                //     $post->user = User::find($post->user_id)->select('id','first_name','last_name' ,'photo')->first();
                //     $posts->comments = $post->comments;
                //     $posts->likes = $post->likes;
                //     $posts->saves = $post->saves;
                //     $posts->shares = $post->shares;
                //     $posts->user    = $post->user ;

                // }
                // $posts->with('comments')->with('likes')->with('saves')->with('shares')->with('user')->get();

                return  $this-> returnData( __('message.post_get_all'),'posts' , $posts);

            }else{
                return $this->returnError(__('message.not_post'), [], 404);
            }
        }catch(\Exception $ex){

            return $this->returnError(__('message.error'), [] );

        }

    }



    public function store(Request $request)
    {
        try{


            //validator
            $validator = validator()->make($request->all() , [
                'text' => 'required',
                'photo[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'user_id' => 'required|exists:users,id',
                'video' => 'mimes:mp4,mov,ogg,qt|max:20000',
                'group_id' => 'exists:groups,id',
            ],$this->message());
            if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError( $code ,$validator);
            }

            //get user
            $user = User::where('id', $request->user_id)->first();
            // check public
            if($request->public == 1){
                $request->request->add(['public' => 1]);
            }else{
                $request->request->add(['public' => 0]);
            }

            // check frind
            if($request->frind == 1){
                $request->request->add(['frind' => 1]);
            }else{
                $request->request->add(['frind' => 0]);
            }

            // check only
            if($request->only == 1){
                $request->request->add(['only' => 1]);
            }else{
                $request->request->add(['only' => 0]);
            }

            // save photo
            if($request->hasFile('photo')){
                if(is_array($request->photo) ){
                    foreach($request->photo as $photo){
                    $name[]   = $this->saveImage($photo , 'post_photo');
                    }
                }else{
                    $name[]   = $this->saveImage($request->photo , 'post_photo');

                }
            }else{
                $name = null;
            }

            // save video
            if($request->hasFile('video')){
                $video = $this->save_videos($request->video , 'post_video');
             }else{
                $video = null;
            }

            // check if user in group
            if($request->group_id){
                $group = users_group::where('user_id', $request->user_id)->where('group_id', $request->group_id)->first();
                if(!$group){
                    return $this->returnError(__('message.user_not_group'),  404);
                }
            }


            //create post
            DB::beginTransaction();
            $post = post::create([
                'text' => $request->text,
                'photo' => $name,
                'user_id' => $request->user_id,
                'public' => $request->public,
                'frind' => $request->frind,
                'only' => $request->only,
                'video' => $video,
                'group_id' => $request->group_id,
            ]);
            $user->update(['last_seen' => now()]);
            DB::commit();


            // check if post created
            if($post){
                return $this->returnData(__('message.s_create_post'), 'post', $post);
            }else{
                return $this->returnError(__('message.r_create_post'),  500);
            }
        }catch(\Exception $ex){
            DB::rollBack();
            // return $this->returnError($ex->getMessage(),  500);
            return $this->returnError(__('message.error'), [] );
        }
    }


    public function show($id)
    {
        try{

            $post = post::find($id);
            $user = User::find($post->user_id)->select('id','first_name','last_name' ,'photo')->first() ;
            if($post){
                $post->comments = comment::where('post_id', $post->id)->get() ;
                $post->likes = like::where('post_id', $post->id)->get() ;
                $post->save_post = save_psot::where('post_id', $post->id)->get() ;
                $post->share_post = share_post::where('post_id', $post->id)->get() ;
                $post->user    = $user ;

                $user->update(['last_seen' => now()]);
                return $this->returnData(__('message.post_found'), 'post', $post);
                        }else{
                return $this->returnError(__('message.not_post'), [], 404);
            }
        }catch(\Exception $ex){
            // return $this->returnError($ex->getCode(),$ex->getMessage());
            return $this->returnError(__('message.error'), [] );

        }

    }


    public function update(Request $request, $id)
    {
        try{
            $post = post::find($id);
            if($post){
            //validator
            $validator = validator()->make($request->all() , [
                'text' => 'required',
                'photo[]' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'user_id' => 'required',
                'video' => 'mimes:mp4,mov,ogg,qt|max:20000',

            ],$this->message());

            if($validator->fails()){
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError( $code ,$validator);

            }
            //check if user exist
             $user = User::where('id', $request->user_id)->first();
            if(!$user){
                return $this->returnError(__('message.user_ont_found'), [], 404);
            }
            $user->update(['last_seen' => now()]);

            // check public
            if($request->public == 1){
                $request->request->add(['public' => 1]);
            }else{
                $request->request->add(['public' => 0]);
            }

            // check frind
            if($request->frind == 1){
                $request->request->add(['frind' => 1]);
            }else{
                $request->request->add(['frind' => 0]);
            }

            // check only
            if($request->only == 1){
                $request->request->add(['only' => 1]);
            }else{
                $request->request->add(['only' => 0]);
            }

            // save photo

            if($request->hasFile('photo')){

                if(is_array($request->photo) ){
                    foreach($request->photo as $photo){

                    $name[]   = $this->saveImage($photo , 'post_photo');
                    }
                }else{
                    $name[]   = $this->saveImage($request->photo , 'post_photo');

                }



            }else{
                $photo = json_decode($post->photo) ;
            }

            // save video
            if($request->hasFile('video')){
                $video = $this->save_videos($request->video , 'post_video');
            }else{
                $video = $post->video;
            }

            //create post
            $post->update([
                'text' => $request->text,
                'photo' => $photo ,
                'user_id' => $request->user_id,
                'public' => $request->public,
                'frind' => $request->frind,
                'only' => $request->only,
                'video' => $video,
            ]);


            // check if post updated
            if($post){

                return $this->returnData(__('message.s_post_update'), 'post', $post);
            }else{
                return $this->returnError(__('message.r_post_update'), [], 500);
            }

            }else{
                return $this->returnError(__('message.not_post'), [], 404);
            }

        }catch(\Exception $e){
            // return $this->returnError($e->getMessage(), [], 500);
            return $this->returnError(__('message.error'), [] );


        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $post = post::find($id);

            if($post){
                DB::beginTransaction();
                $post->comments()->delete();
                $post->likes()->delete() ;
                $post->saves()->delete() ;
                $post->shares()->delete() ;
                $post->delete();
                DB::commit();
                return $this->returnData(__('message.s_post_delete'), 'post', $post);
            }else{
                return $this->returnError(__('message.r_post_delete'), [], 500);
            }
        }catch(\Exception $e){
            DB::rollBack();
            // return $this->returnError($e->getMessage(), [], 500);
            return $this->returnError(__('message.error'), [] );

        }


    }



}
