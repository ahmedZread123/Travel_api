<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class album extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'photo', 'description', 'user_id'
    ];


    public function setphotoAttribute($photo)
    {
        $this->attributes['photo'] = json_encode($photo);
    }
}
