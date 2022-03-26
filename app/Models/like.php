<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class like extends Model
{
    use HasFactory;
    protected $fillable = 
    ['post_id', 
    'user_id', 
    'active', 
    
   ];

    public function post()
    {
        return $this->belongsTo(post::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }
    
}
