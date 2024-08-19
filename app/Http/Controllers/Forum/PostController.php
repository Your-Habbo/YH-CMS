<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumPost;
use App\Models\Forum\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Forum\PostEditHistory;

class PostController extends Controller
{
    public function reply(Request $request, ForumThread $thread)
    {
        $reply = ForumPost::create([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'content' => $request->content,
        ]);

        $notification = new ForumReplyNotification($thread, $reply);
        $this->notificationService->create($thread->user, 'forum_reply', $notification->toArray());

        return redirect()->back()->with('success', 'Reply posted successfully.');
    }

    public function editPost(Request $request, ForumPost $post)
    {
        if (!Gate::allows('update-post', $post)) {
            return response()->json(['error' => 'You are not authorized to edit this post.'], 403);
        }

        $request->validate(['content' => 'required|string']);
        $oldContent = $post->content;

        $post->update(['content' => $request->content, 'is_edited' => true]);

        PostEditHistory::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'old_content' => $oldContent,
            'new_content' => $request->content,
        ]);

        return response()->json(['success' => true]);
    }

    public function getPostEditHistory(ForumPost $post)
    {
        if (!Gate::allows('view-post', $post)) {
            return response()->json(['error' => 'You are not authorized to view this post\'s history.'], 403);
        }

        $history = $post->editHistory()->with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($history);
    }

    public function showEditHistory(ForumPost $post)
    {
        if (!Gate::allows('view-post', $post)) {
            abort(403, 'You are not authorized to view this post\'s history.');
        }

        $editHistory = $post->editHistory()->with('user')->orderBy('created_at', 'desc')->get();
        return view('forum.edit-history', compact('post', 'editHistory'));
    }
}
