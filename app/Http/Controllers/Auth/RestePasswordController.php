<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\forgetPasswordEmail;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RestePasswordController extends Controller
{

    use GeneralTrait ;
    // forget passwoed

    public function forget_password(Request $request){
        // valedator
        $validator = validator()->make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ] , $this->message());
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError( $code ,$validator);
        }

        // send code in email
        $user = User::where('email', $request->email)->first();
        $code = rand(1111, 9999);
        $user->update(['code' => $code]);

        Mail::to($user->email)->send(new forgetPasswordEmail($code));
        return $this->returnSuccessMessage(__('auth.email_success')) ;
        return $this->returnData(__('auth.email_success') , 'user_id' , $user->id);

    }

    // check code

    public function check_code(Request $request){
        // valedator
        $validator = validator()->make($request->all(), [
            'code' => 'required',
            'user_id' => 'required|exists:users,id',
        ] , $this->message());
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError( $code ,$validator);
        }

        // check code
        $user = User::find($request->user_id);

        if ($user->code == $request->code) {
            return $this->returnData( __('auth.code_success') , 'user' ,$user);
        }else{
            return $this->returnError( __('auth.code_error') , 500);
        }
    }

    // reset password
    public function reset_password(Request $request){
        // valedator
        $validator = validator()->make($request->all(), [
            'user_id'  => 'required|exists:users,id',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password',
        ] , $this->message());
        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError( $code ,$validator);
        }

        // reset password

        $user = User::find($request->user_id);
        $user->update(['password' => Hash::make($request->password)]);
        return $this->returnSuccessMessage(__('auth.password_reset_successy'));

    }



}
