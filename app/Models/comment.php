<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneralTrait ;
class comment extends Model
{
    use HasFactory;
    use GeneralTrait;

    protected $fillable = [
        'comment', 'user_id', 'post_id',
    ];

    // USER COMMENTED POST

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // POST COMMENTED

    public function post()
    {
        return $this->belongsTo(post::class);
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
