<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class frend extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'frend_id','is_accepted'
    ];

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function frend()
    {
        return $this->belongsTo(user::class , 'frend_id');
    }

    public function user_room()
    {
        return $this->hasMany(user_room::class);
    }




}
