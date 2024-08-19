<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ForumPost;
use App\Models\Forum\ThreadLike;
use App\Models\Forum\PostLike;

class LikeController extends Controller
{
    public function likeThread(ForumThread $thread)
    {
        $like = $thread->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $thread->decrement('likes_count');
            return response()->json(['likes_count' => $thread->likes_count, 'liked' => false]);
        } else {
            ThreadLike::create([
                'thread_id' => $thread->id,
                'user_id' => auth()->id(),
            ]);
            $thread->increment('likes_count');
            return response()->json(['likes_count' => $thread->likes_count, 'liked' => true]);
        }
    }

    public function likePost(ForumPost $post)
    {
        $like = $post->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            return response()->json(['likes_count' => $post->likes_count, 'liked' => false]);
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => auth()->id(),
            ]);
            $post->increment('likes_count');
            return response()->json(['likes_count' => $post->likes_count, 'liked' => true]);
        }
    }
}
