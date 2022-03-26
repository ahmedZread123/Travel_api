<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\posts ;
use App\Models\users_group;

class group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'private', 'public', 'photo', 'describtion','user_id'
    ];

    // user create group
    public function user()
    {
        return $this->belongsTo('App\Models\users', 'user_id');
    }

    // users in group
    public function users()
    {
        return $this->hasMany(users_group::class );
    }

    // posts in group
    public function posts()
    {
        return $this->hasMany('App\Models\post','group_id' );
    }
}
