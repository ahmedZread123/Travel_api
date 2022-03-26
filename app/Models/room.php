<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room extends Model
{
    use HasFactory;

    protected $fillable = [
         'id','title' , 'photo' , 'group'
    ];

    public function message(){
        return $this->hasMany(message::class) ;
    }

    public function user(){
        return $this->hasMany(user_room::class , 'room_id') ;
    }






}
