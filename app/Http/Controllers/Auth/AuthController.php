<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use validator ;
class AuthController extends Controller
{
    use GeneralTrait;

    // login
    public function login(Request $request)
    {
        try{
            // validator
            $validator = validator()->make($request->all(), [
                'email' => 'required|email|exists:users,email',
                'password' => 'required|',
            ] , $this->message());
            if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError( $code ,$validator);
            }

            //  LOGIN and return token
          $token =  Auth::guard('api')-> attempt(['email' => $request->email, 'password' => $request->password]);
            if ($token) {
                $user = Auth::guard('api')->user()->where('email', $request->email)->first();
                $user = User::find($user->id);
                $user->update(['last_seen' => now()]);
                $user->token = $token;

                return $this->returnData(__('auth.login_success'), 'user',  $user);

            } else {
                return $this->returnError('auth.login_error', []);
            }


         }catch(\Exception $e){
            // return $this->returnError($e->getMessage(), [], 400);
            return $this->returnError(__('message.error'), 500);
         }

    }

    // lgo out

    public function logout(Request $request){
        try{

            $token =  $request->header('token') ;
            // return $token ;
            if($token){
                JWTAuth::setToken($token)->invalidate();
                return $this->returnSuccessMessage(__('auth.logout_success'));

            }else{
                return $this->returnError(__('auth.not_token'), []);
            }
        }catch(\Exception $e){

            return $this->returnError(__('message.error'), 500);

        }


    }

    // register
    public function register(Request $request)
    {
        try{
            // validator
            $validator = validator()->make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|string|min:6',

            ] , $this->message());

            if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError( $code ,$validator);
            }

            // check if user exist
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return $this->returnError(__('auth.email_exist'), []);
            }

            //  register
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if($user){
               return $this->returnSuccessMessage(__('auth.register_success')  );

            }else{
                return $this->returnError(__('auth.register_error'), []);
            }

        }catch(\Exception $e){
            // return $this->returnError($e->getMessage(), [], 400);
            return $this->returnError(__('message.error'), 500);

        }



    }


    /*
     three function forget password and check code and reset password ;
    */









}
