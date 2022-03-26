<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\share_post;
use App\Models\like;
use App\Models\comment;
use App\Models\save_psot;
class post extends Model
{
    use HasFactory;

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
        return $this->belongsTo(User::class);
    }

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

    public function setphotoAttribute($photo)
    {
        $this->attributes['photo'] = json_encode($photo);
    }




}
