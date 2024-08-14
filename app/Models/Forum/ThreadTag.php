<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreadTag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'color', 'is_help_tag'];

    public function threads()
    {
        return $this->belongsToMany(ForumThread::class, 'thread_tag', 'tag_id', 'thread_id');
    }
}