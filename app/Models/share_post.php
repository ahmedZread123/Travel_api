<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class share_post extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'post_id',
    ];
    // user share psot 
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    // post share
    public function post()
    {
        return $this->belongsTo(Post::class);
    }




}
