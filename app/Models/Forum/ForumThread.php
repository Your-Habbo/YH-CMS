<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // Import the User model from the correct namespace
use App\Models\Forum\ThreadLike;
use Illuminate\Support\Facades\Auth; 

class ForumThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'title', 'slug', 
        'content', 'is_sticky', 'is_locked', 'view_count',
        'is_guide', 'is_helpful', 'requires_solution', 'is_resolved',
        'is_edited'
    ];

    protected $casts = [
        'is_sticky' => 'boolean',
        'is_locked' => 'boolean',
        'is_guide' => 'boolean',
        'is_helpful' => 'boolean',
        'requires_solution' => 'boolean',
        'is_resolved' => 'boolean',
        'is_edited' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(ForumCategory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(ForumPost::class, 'thread_id');
    }

    public function tags()
    {
        return $this->belongsToMany(ThreadTag::class, 'thread_tag', 'thread_id', 'tag_id');
    }

    public function helpTags()
    {
        return $this->tags()->where('is_help_tag', true);
    }

    public function markAsResolved()
    {
        $this->update(['is_resolved' => true]);
    }

    public function editHistory()
    {
        return $this->hasMany(ThreadEditHistory::class, 'thread_id');
    }

    public function markAsEdited()
    {
        $this->update(['is_edited' => true]);
    }
    public function likes()
    {
        return $this->hasMany(ThreadLike::class, 'thread_id'); // Make sure 'thread_id' is used as the foreign key
    }

    public function userHasLiked()
    {
        return $this->likes()->where('user_id', Auth::id())->exists();
    }
}