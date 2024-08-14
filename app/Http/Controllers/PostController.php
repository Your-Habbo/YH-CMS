<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ForumPost;

class PostController extends Controller
{
    public function store(Request $request, ForumThread $thread)
    {
        // Validate the request
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        // Create a new post associated with the thread
        ForumPost::create([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'content' => $validated['content'],
        ]);

        // Redirect back to the thread with a success message
        return redirect()->route('forum.show', $thread->slug)->with('success', 'Your reply has been posted.');
    }
}
