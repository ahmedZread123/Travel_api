<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\share_post;
use App\Models\like;
use App\Models\comment;
use App\Models\save_psot;
use App\Traits\GeneralTrait;
class post extends Model
{
    use HasFactory;
    use GeneralTrait;
    protected $fillable = [
        'text',
        'photo',
        'public',
        'frind',
        'only',
        'user_id',
        'video',
        'group_id' ,
    ];

    // user in post
    public function user()
    {
        return  $this->belongsTo(User::class );
    }

    // get user  post


    // comments in post
    public  function comments()
    {
        return $this->hasMany(comment::class);
    }

    // like in post
    public  function likes()
    {
        return $this->hasMany(like::class);
    }

    // save post
    public  function saves()
    {
        return $this->hasMany(save_psot::class);
    }

    // share post
    public function shares(){
        return $this->hasMany(share_post::class) ;
    }

    // photo in post
    public function setphotoAttribute($photo)
    {
        $this->attributes['photo'] = json_encode($photo);
    }

    public function getphotoAttribute($photo)
    {
        return json_decode($photo);
    }

    // video in post
    public function setvideoAttribute($video)
    {
        $this->attributes['video'] = json_encode($video);
    }

    public function getvideoAttribute($video)
    {
        return json_decode($video);
    }


    // created_at
        public function getCreatedAtAttribute(){
        return $this->time($this->attributes['created_at']);
    }

    // updated_at
    public function getUpdatedAtAttribute(){
        return $this->time($this->attributes['updated_at']);
    }




}
