<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneralTrait ;
class like extends Model
{
    use HasFactory;
    use GeneralTrait;
    protected $fillable =
    [
    'post_id',
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

     // created_at
     public function getCreatedAtAttribute(){
        return $this->time($this->attributes['created_at']);
    }

    // updated_at
    public function getUpdatedAtAttribute(){
        return $this->time($this->attributes['updated_at']);
    }

}
