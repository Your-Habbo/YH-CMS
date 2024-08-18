<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Forum\PostLike;
use Illuminate\Support\Facades\Auth; 

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'thread_id', 'content', 'is_solution', 'is_edited'];
    
    protected $casts = [
        'is_solution' => 'boolean',
        'is_edited' => 'boolean',
    ];

    public function thread()
    {
        return $this->belongsTo(ForumThread::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function editHistory()
    {
        return $this->hasMany(PostEditHistory::class, 'post_id');
    }

    public function markAsEdited()
    {
        $this->update(['is_edited' => true]);
    }
    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id'); // Ensure 'post_id' is used as the foreign key
    }
    
    public function userHasLiked()
    {
        return $this->likes()->where('user_id', Auth::id())->exists();
    }
}