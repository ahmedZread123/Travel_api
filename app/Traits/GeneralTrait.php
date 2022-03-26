<?php

namespace App\Traits;

use Cloudinary\Cloudinary;
use Sami\Parser\Filter\CloudinaryFilter;

trait GeneralTrait
{
    ////////////////////////////////////////////////////////////////////////
    public function getCurrentLang()
    {
        return app()->getLocale();
    }

    ////////////////////////////////////////////////////////////////////////

    public function setCurrentLang($lang)
    {
        app()->setLocale($lang);
    }

    ////////////////////////////////////////////////////////////////////////
    public function returnError($msg = "", $errNum)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    ////////////////////////////////////////////////////////////////////////
    public function returnSuccessMessage($msg = "")
    {
        return [
            'status' => true,
            'errNum' => '',
            'msg' => $msg
        ];
    }

    ////////////////////////////////////////////////////////////////////////
    public function returnData($msg = "", $key, $value )
    {
        return response()->json([
            'status' => true,
            'errNum' => "",
            'msg' => $msg,
            $key => $value ,



        ]);
    }

    ////////////////////////////////////////////////////////////////////////
    public function returnValidationError($code = "", $validator)
    {
        return $this->returnError( $validator->errors()  , $code);
    }

    ////////////////////////////////////////////////////////////////////////
    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    ////////////////////////////////////////////////////////////////////////
    public function getErrorCode($input)
    {
        if ($input == "name_ar")
            return 'E0011';

        else if ($input == "password")
            return 'E002';

        else
            return "404";
    }

    ////////////////////////////////////////////////////////////////////////
    public function saveImage($name , $path){
        $ImageExtenstion = $name->getClientOriginalExtension();
        $ImageName = time().rand().'.'.$ImageExtenstion;
        $name->move($path,$ImageName);
        return $ImageName;
    }


    ///////////////////////////////////////////////////////////////////////
    public function save_videos($name , $path){
        // $name = $request->video;
        $filename = time().rand().'.'.$name->getClientOriginalName();
        $name->move(public_path($path), $filename);
        return $filename ;

    }


    public function message(){
        return [
            'name.required'        => __('validation.name_required'),
            'text.required'        => __('validation.text_required'),
            'email.required'       => __('validation.email_required'),
            'email.email'          =>  __('validation.email_email'),
            'password.required'    =>  __('validation.password_required'),
            'password.min'         => __('validation.password_min'),
            'password.confirmed'   =>  __('validation.password_confirmed'),
            'phone.required'       =>  __('validation.phone_required'),
            'phone.numeric'        =>  __('validation.phone_numeric'),
            'phone.unique'         =>  __('validation.phone_unique'),
            'phone.digits'         =>  __('validation.phone_digits'),
            'phone.regex'          =>  __('validation.phone_regex'),
            'image.required'       =>  __('validation.image_required'),
            'image.image'          =>  __('validation.image_image'),
            'image.mimes'          =>  __('validation.image_mimes'),
            'image.max'            =>  __('validation.image_max'),
            'video.required'       =>  __('validation.video_required'),
            'video.mimes'          =>  __('validation.video_mimes'),
            'video.max'            =>  __('validation.video_max'),
            'user_id.required'     =>  __('validation.user_id_required'),
            'user_id.exists'       =>  __('validation.user_id_exists'),
            'group_id.exists'      =>  __('validation.group_id_exists'),
            'group_id.required'    =>  __('validation.group_id_required'),
            'comment.required'     =>  __('validation.comment_required'),
            'post_id.required'     =>  __('validation.post_id_required'),
            'post_id.exists'       =>  __('validation.post_id_exists'),
            'active.required'      =>  __('validation.active_required'),
            'active.boolean'       =>  __('validation.active_boolean'),
            'name.string'          => __('validation.name_string'),
            'name.max'             =>  __('validation.name_max'),
            'private.integer'      =>  __('validation.private_integer'),
            'private.in'           =>  __('validation.private_in'),
            'public.integer'       =>  __('validation.public_integer'),
            'public.in'            =>  __('validation.public_in'),
            'describtion.string'   => __('validation.describtion_string'),
            'describtion.max'      =>  __('validation.describtion_max'),
            'invite_from.required' =>  __('validation.invite_from_required'),
            'invite_from.exists'   =>  __('validation.invite_from_exists'),
            'invite_to.required'   => __('validation.invite_to_required'),
            'invite_to.exists'     =>  __('validation.invite_to_exists'),
            'address.string'       =>  __('validation.address_string'),
            'address.required'     =>  __('validation.address_required'),
            'address.max'          =>  __('validation.address_max'),
            'code.required'        =>  __('validation.code_required'),
        ] ;
    }

}
