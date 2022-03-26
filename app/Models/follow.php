<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'follow_to',
        'active',
    ];
}
