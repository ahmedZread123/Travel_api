<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject  , MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'photo',
        'last_seen',
        'email_verified_at' ,
        'remember_token' ,
        'code' ,
        'facebook_id' ,
        'google_id' ,
        'phone' ,
        'address' ,
        'private' ,

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // save post
    public function save_post()
    {
        return $this->hasMany(save_psot::class);
    }

    // share post
    public function share_post()
    {
        return $this->hasMany(share_post::class);
    }

    // frend
    public function frends()
    {
        return $this->hasMany(frend::class , 'user_id' );
    }

    // frend
    public function frends_to()
    {
        return $this->hasMany(frend::class , 'frend_id' );
    }



    // user room
    public function user_room()
    {
        return $this->hasMany(user_room::class , 'user_id');
    }

    // followers
    public function followers()
    {
       return  $this->hasMany(follow::class , 'user_id');

    }

    // following
    public function following()
    {
        return $this->hasMany(follow::class , 'follow_to');
    }

    // profile
    public function profile()
    {
        return $this->hasOne(profile::class);
    }



}
