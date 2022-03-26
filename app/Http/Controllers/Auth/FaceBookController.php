<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class FaceBookController extends Controller
{
    public function loginUsingFacebook()
    {
       return Socialite::driver('facebook')->redirect();
    }


    public function callbackFromFacebook()
    {
     try {
          $user = Socialite::driver('facebook')->user();

          $saveUser = User::updateOrCreate([
              'facebook_id' => $user->getId(),
          ],[
              'name' => $user->getName(),
              'email' => $user->getEmail(),
              'password' => Hash::make($user->getName().'@'.$user->getId())
               ]);

            $token = JWTAuth::fromUser($saveUser);

        //   $token =  Auth::guard('api')-> attempt(['email' => $request->email, 'password' => $request->password]);
          if ($token) {
              $user = Auth::guard('api')->user()->where('email', $saveUser->email)->first();
              $user = User::find($user->id);
              $user->update(['last_seen' => now()]);
              $user->token = $token;

              return $this->returnData(' تم تسجيل الدخول بواسطة فيس بوك  بنجاح', 'user',  $user);
          }


          } catch (\Throwable $th) {
             throw $th;

          }
      }



}
