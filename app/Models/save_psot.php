<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneralTrait;
class save_psot extends Model
{
    use HasFactory;
    use GeneralTrait ;

    protected $fillable = [
        'user_id', 'post_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
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
