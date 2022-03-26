<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personal_access_token extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'token', 'abilities', 'last_used_at',
    ];

    public function tokenable()
    {
        return $this->morphTo();
    }

    public function scopeOfUser($query, $user)
    {
        return $query->where('tokenable_id', $user->id)
            ->where('tokenable_type', User::class);
    }

    public function scopeOfClient($query, $client)
    {
        return $query->where('tokenable_id', $client->id)
            ->where('tokenable_type', Client::class);
    }
}
