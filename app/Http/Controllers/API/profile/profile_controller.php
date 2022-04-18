<?php

namespace App\Http\Controllers\API\profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class profile_controller extends Controller
{
    /*
      function
        1- get profile
        2- update profile
        3- get post profile
        4- get album profile
        5- get statis profile
        6- get about profile

    */

 use GeneralTrait ;
     // get profile
    public function get_profile($id){
        try{
            $user = User::find($id);
            if($user){

            $profile = $user->profile()->first();
            $profile->followers= $user->followers()->count();
            $profile->following= $user->following()->count();

            if($profile){
                return $this->returnData('Get All Data In Profile ' , 'profile', $profile);
            }else{
                return $this->returnError('Profile Not Found', 404);
            }
            }else{
                return $this->returnError(__('message.user_not_found') , 404);
            }
        }catch(\Exception $e){
            // return response()->json(['error' => $e->getMessage()], 500);
            return $this->returnError(__('message.error') , 500);
        }

    }

    // update profile
    public function update_profile(Request $request , $id){
        try{
            $user = User::find($id);
            if($user){
                $profile = $user->profile()->first();
                if($profile){

                    // save photo
                    if($request->hasFile('photo')){
                        $photo = $this->saveImage($request->photo,  'photo_user');
                    }else{
                        $photo = $profile->photo;
                    }

                    if($request->hasFile('header_photo')){
                        $header_photo = $this->saveImage($request->header_photo,  'photo_profile');
                    }else{
                        $header_photo = $profile->header_photo;
                    }

                    $profile->update([
                        'name' => $request->name,
                        'birth_day' => $request->birth_day,
                        'nationality' => $request-> nationality  ,
                        'language' => $request->  language ,
                        'host' => $request-> host  ,
                        'interst' => $request->  interst ,
                        'bio' => $request->  bio ,
                        'gender'=>$request->gender ,
                        'header_photo' => $header_photo ,
                        'photo'        => $photo ,

                    ]);

                    return $this->returnData('Update Profile Successfully' , 'profile', $profile);
                }else{
                    return $this->returnError('Profile Not Found', 404);
                }
            }else{
                return $this->returnError(__('message.user_ont_found') , 404);
            }
        }catch(\Exception $e){
            // return response()->json(['error' => $e->getMessage()], 500);
            return $this->returnError(__('message.error') , 500);

        }

    }


    // get post profile
    public function get_post_profile($id){
        try{
            $user = User::find($id);
            if($user){
                $posts = $user->posts()->get();
                if($posts){
                    foreach($posts as $key => $post){
                        $posts[$key]->comment = $post->comments()->get();
                        $posts[$key]->like = $post->likes()->get();
                        $posts[$key]->share = $post->shares()->get();
                        $posts[$key]->saves = $post->saves()->get();
                    }
                    return $this->returnData('Get All Data In Post ' , 'post', $posts);
                }else{
                    return $this->returnError('Post Not Found', 404);
                }
            }else{
                return $this->returnError(__('message.user_ont_found') , 404);
            }
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    // get album profile
    public function get_album_profile($id){
        try{
            $user = User::find($id);
            if($user){
                $albums = $user->album()->get();

                if($albums){
                    // foreach($albums as $key => $album){
                    //     $albums[$key]->photo = $album->photo()->get();
                    // }
                    return $this->returnData('Get All Data In Album ' , 'albums', $albums);
                }else{
                    return $this->returnError('Album Not Found', 404);
                }
            }else{
                return $this->returnError(__('message.user_not_found') , 404);
            }
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    // get about profile
    public function get_about_profile($id){
        try{
            $user = User::find($id);
            if($user){
                $about = $user->profile()->get();
                if($about){
                    return $this->returnData('Get All Data In About ' , 'about', $about);
                }else{
                    return $this->returnError('About Not Found', 404);
                }
            }else{
                return $this->returnError(__('message.user_not_found') , 404);
            }
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    // get statis profile





}
