<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Model;

class ThreadLike extends Model
{
    protected $fillable = ['thread_id', 'user_id'];

    public function thread()
    {
        return $this->belongsTo(ForumThread::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
