<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'country', 'name', 'start_date', 'end_date', 'public', 'private', 'description','user_id'
    ];

}
