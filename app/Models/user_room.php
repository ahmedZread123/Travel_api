<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_room extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'room_id',
    ];

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function room()
    {
        return $this->belongsTo(room::class);
    }
}
