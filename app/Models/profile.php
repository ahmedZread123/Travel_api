<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneralTrait;
use Carbon\Carbon;

class profile extends Model
{
    use HasFactory;
    use GeneralTrait;

    protected $fillable = [
        'user_id', 'name', 'email', 'phone', 'address', 'photo', 'about', 'status','created_at' ,'updated_at'
    ];
     // created_at
    public function getCreatedAtAttribute(){
        return $this->time($this->attributes['created_at']);
    }

    // updated_at
    public function getUpdatedAtAttribute(){
        return $this->time($this->attributes['updated_at']);
    }
}
