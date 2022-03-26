<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'room_id', 'message', 'reed',
    ];

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function room()
    {
        return $this->belongsTo(room::class);
    }

    public function frend()
    {
        return $this->belongsTo(frend::class);
    }


}
