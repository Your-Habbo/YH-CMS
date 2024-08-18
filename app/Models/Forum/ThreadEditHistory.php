<?php


namespace App\Models\Forum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ThreadEditHistory extends Model
{
    use HasFactory;

    protected $fillable = ['thread_id', 'user_id', 'old_title', 'new_title', 'old_content', 'new_content'];

    public function thread()
    {
        return $this->belongsTo(ForumThread::class, 'thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}