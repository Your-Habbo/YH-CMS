<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $fillable = ['post_id', 'user_id'];

    public function post()
    {
        return $this->belongsTo(ForumPost::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class); // Ensure User is correctly referenced
    }
}
