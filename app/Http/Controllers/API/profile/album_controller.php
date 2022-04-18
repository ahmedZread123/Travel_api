<?php

namespace App\Http\Controllers\API\profile;

use App\Http\Controllers\Controller;
use App\Models\album;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\GeneralTrait;
class album_controller extends Controller
{
    /*
        function
            1- create album
    */

    use GeneralTrait ;

    // create album
    public function create_album(Request $request){
        try{
            // return $request->all() ;

           // validate the request
              $validator = validator()->make($request->all(), [
                 'name' => 'required|string|max:255',
                 'photo[]' => 'image|mimes:png,jpg,jpeg,gif|max:2048',
                 'description' => 'required|string',
                 'user_id' => 'required|exists:users,id',
                ] , $this->message());
                if($validator->fails()){
                    $code = $this->returnCodeAccordingToInput($validator);
                    return $this->returnValidationError( $code ,$validator);
                }

                if($request->hasFile('photo') == null){
                  return $this->returnError(__('validation.required') , 404);
                }

                $user = User::find($request->user_id);
                if($user){
                    // save photo
                    if($request->hasFile('photo')){
                        if(is_array($request->photo) ){
                            foreach($request->photo as $photo){
                            $name[]   = $this->saveImage($photo , 'album_photo');
                            }
                        }else{
                            $name[]   = $this->saveImage($request->photo , 'post_photo');

                        }
                    }else{
                        $name = null;
                    }

                    $album = album::create([
                        'name' => $request->name,
                        'photo' =>$name,
                        'description' => $request->description,
                        'user_id' => $request->user_id,

                    ]);

                 if($album){
                      return $this->returnData('Create Album Successfully', 'album', $album);
                 }else{
                      return $this->returnError('Create Album Unsuccessfully', 500);
                 }
                }else{
                 return $this->returnError(__('message.user_not_found') , 404);
                }
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

}
