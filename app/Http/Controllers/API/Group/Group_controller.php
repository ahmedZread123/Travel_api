<?php

namespace App\Http\Controllers\API\Group;

use App\Http\Controllers\Controller;
use App\Models\group;
use App\Models\User;
use App\Models\users_group;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\comment;
use App\Models\like;
use App\Models\save_psot;
use App\Models\share_post;
class Group_controller extends Controller
{
    use GeneralTrait ;

    /*
        table in data = group - invite_user - user_group

        create  group انشاء مجموعة
        [name - private OR public - photo - describtion  ]

        invite to group  دعوة إلى المجموعة
        [invite_from - invite_to -group_id  - date]

        user_group   المستخدمين داخل  المجموعة
        [user_id - group_id  - date]

        function
            1- create group
            2- jone user to group (if public )
            3- invite user to group

            4- delete user from group -
            5- delete group -
            6- edit group -

            7- get group info
            9- get group with user and post

            10- accept invite to group
            11- not accept invite to group

    */

     //  create group
    public function create_group(Request $request)
    {
        try{
            $validator = validator()->make($request->all(), [
                'name' => 'required|string|max:255',
                'private' => 'in:0,1',
                'public' => 'in:0,1',
                'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'describtion' => 'string|max:255',
                'user_id' => 'required|exists:users,id',
            ], $this->message());

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            // check is private or public
            if(!$request->has('private') && !$request->has('public')){
                return $this->returnError(_('message.publicorprivate'), 404);
            }
            // check is private
            if($request->has('private')){
                if($request->private == 1){
                    $private = 1;
                }else{
                    $private = 0;
                }
             }else{
                $private = 0;
             }

            // check is public
            if($request->has('public')){
                if($request->public == 1){
                  $public  = 1;
                }else{
                    $public = 0;
             }
             }else{
                    $public = 0;
            }

            // save photo
            if($request->has('photo')){
                $photo = $this->saveImage($request->photo , 'photo_group');
            }else{
                $photo = null;
            }


            DB::beginTransaction();
            // create
            $group = group::create([
                'name' => $request->name,
                'private' => $private,
                'public' => $public,
                'photo' => $photo,
                'describtion' => $request->describtion,
                'user_id' => $request->user_id,
            ]);

            // join user to group
            if($group){
                    $user_group = users_group::create([
                        'user_id' => $request->user_id,
                        'group_id' => $group->id,
                    ]);
            }
            $user = User::find($request->user_id);
            $user->update(['last_seen' => now()]);
            DB::commit();

            if($group){
                return $this->returnData(__('message.s_create_group'),'group' , $group);
            }else{
                return $this->returnError(__('message.s_create_group'), 404);
            }

        }catch (\Exception $ex){
            DB::rollBack();
            return $this->returnError(__('message.error') ,500);
        }


    }
      // join user to group
    public function join_group(Request $request)
    {
        try{
            $validator = validator()->make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'group_id' => 'required|exists:groups,id',
            ] , $this->message());

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
             // check if user is in group
            $user_group = users_group::where('user_id',$request->user_id)->where('group_id',$request->group_id)->first();
            if($user_group){
                return $this->returnError(__('message.user_group') , '201');
            }
            // check if group is public
            $group = group::where('id',$request->group_id)->where('public',1)->first();
            if($group){
               // create
               DB::beginTransaction();
                $user_group = users_group::create([
                    'user_id' => $request->user_id,
                    'group_id' => $request->group_id,
                ]);
                $user = User::find($request->user_id);
                $user->update(['last_seen' => now()]);
                DB::commit();
                if($user_group){
                    return $this->returnData(__('message.join_group'),'user_group' , $user_group);
                }
           }else{
                return $this->returnError(__('message.not_public_group') , 404);
            }

        }catch (\Exception $ex){
            DB::rollBack();
            // return $this->returnError($ex->getMessage() , $ex->getCode());
            return $this->returnError(__('message.error') , 500);
        }
    }


     // get group info
    public function get_group_public(){
        try{
            $groups = group::where('public',1)->get();
            if($groups){

                foreach ($groups as $key => $group){
                    $user = User::find($group->user_id);
                    $groups[$key]->user = $user;
                }

                return $this->returnData('groups' , 'groups' , $groups);
            }


        }catch (\Exception $ex){
            // return $this->returnError($ex->getMessage() , $ex->getCode());
            return $this->returnError(__('message.error') ,500);
        }
    }

    // get group with user and post
    public function get_group_with_user_and_post($id)
    {
        try{
            $group = group::find($id)->first();
            if($group){
                $group->users = $group->users()->get();
                $posts = $group->posts()->get();
                foreach($posts as $key =>$post){
                    $post->comments = comment::where('post_id', $post->id)->get() ;
                    $post->likes = like::where('post_id', $post->id)->get() ;
                    $post->save_post = save_psot::where('post_id', $post->id)->get() ;
                    $post->share_post = share_post::where('post_id', $post->id)->get() ;
                }
                $group->posts = $posts;

                return $this->returnData('group' , 'group' , $group);
            }else{
                return $this->returnError(__('message.not_group'), 404);
            }


        }catch (\Exception $ex){
            // return $this->returnError($ex->getMessage() , $ex->getCode());
            return $this->returnError(__('message.error') , 500);
        }
    }








}
