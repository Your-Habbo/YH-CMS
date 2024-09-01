<?php

namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Forum\PostLike;
use Illuminate\Support\Facades\Auth; 
use Spatie\Permission\Models\Permission;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'thread_id', 'content', 'is_solution', 'is_edited', 'deleted_at'];
    
    protected $casts = [
        'is_solution' => 'boolean',
        'is_edited' => 'boolean',
    ];

    protected $dates = ['deleted_at'];

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

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('notDeleted', function (Builder $builder) {
            if (!Auth::check() || !self::userCanSeeDeletedPosts()) {
                $builder->whereNull('deleted_at');
            }
        });
    }

    public static function userCanSeeDeletedPosts()
    {
        static $canSeeDeletedPosts = null;

        if ($canSeeDeletedPosts === null) {
            $permission = Permission::where('name', 'see deleted posts')->first();
            $canSeeDeletedPosts = $permission && Auth::user()->hasPermissionTo($permission);
        }

        return $canSeeDeletedPosts;
    }

    public function scopeWithDeleted($query)
    {
        return $query->withoutGlobalScope('notDeleted');
    }

    public function scopeOnlyDeleted($query)
    {
        return $query->withoutGlobalScope('notDeleted')->whereNotNull('deleted_at');
    }

}