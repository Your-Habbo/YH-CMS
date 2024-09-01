<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumPost;
use App\Models\Forum\ForumThread;
use App\Models\Forum\PostLike;
use App\Models\Forum\ThreadLike;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function likePost(ForumPost $post)
    {
        $like = $post->likes()->where('user_id', Auth::id())->first();
    
        if ($like) {
            // Unlike the post
            $like->delete();
            $post->decrement('likes_count');
            return response()->json(['success' => true, 'likes_count' => $post->likes_count, 'liked' => false]);
        } else {
            // Like the post
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => Auth::id(),
            ]);
            $post->increment('likes_count');
            return response()->json(['success' => true, 'likes_count' => $post->likes_count, 'liked' => true]);
        }
    }   

    public function unlikePost(ForumPost $post)
    {
        $like = $post->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            // Unlike the post
            $like->delete();
            $post->decrement('likes_count');
            return response()->json(['success' => true, 'likes_count' => $post->likes_count, 'liked' => false]);
        } else {
            // If the like doesn't exist, return an error response
            return response()->json(['success' => false, 'message' => 'Like not found'], 404);
        }
    }

    

    public function likeThread(ForumThread $thread)
    {
        $like = $thread->likes()->where('user_id', Auth::id())->first();
    
        if ($like) {
            // Unlike the thread
            $like->delete();
            $thread->decrement('likes_count');
            return response()->json(['success' => true, 'likes_count' => $thread->likes_count, 'liked' => false]);
        } else {
            // Like the thread
            ThreadLike::create([
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
            ]);
            $thread->increment('likes_count');
            return response()->json(['success' => true, 'likes_count' => $thread->likes_count, 'liked' => true]);
        }
    }

    public function unlikeThread(ForumThread $thread)
    {
        try {
            $like = $thread->likes()->where('user_id', Auth::id())->first();

            if ($like) {
                // Unlike the thread
                $like->delete();
                $thread->decrement('likes_count');
                return response()->json(['success' => true, 'likes_count' => $thread->likes_count, 'liked' => false]);
            } else {
                // If the like doesn't exist, return an error response
                return response()->json(['success' => false, 'message' => 'Like not found'], 404);
            }
        } catch (\Exception $e) {
            // Return a JSON response if an error occurs
            return response()->json(['success' => false, 'message' => 'An error occurred while processing your request'], 500);
        }
    }

    
}
