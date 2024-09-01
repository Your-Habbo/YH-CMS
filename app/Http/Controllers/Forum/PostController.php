<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumPost;
use App\Models\Forum\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Forum\PostEditHistory;
use App\Notifications\ForumReplyNotification;
use Purifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Permission;

class PostController extends Controller
{   

    use AuthorizesRequests;


    public function reply(Request $request, ForumThread $thread)
    {
        $request->validate([
            'content' => 'required|string|max:5000',
        ]);
    
        // Rate limiting: Check if the user has already posted within the last minute
        $recentPost = ForumPost::where('user_id', auth()->id())
                               ->where('thread_id', $thread->id)
                               ->where('created_at', '>=', now()->subMinute())
                               ->first();
    
        if ($recentPost) {
            return redirect()->back()->with('error', 'You are posting too quickly. Please wait a moment before posting again.');
        }
    
        // Prevent duplicate content: Check if the user has already posted this exact content recently
        $duplicatePost = ForumPost::where('user_id', auth()->id())
                                  ->where('thread_id', $thread->id)
                                  ->where('content', $request->input('content'))
                                  ->where('created_at', '>=', now()->subMinutes(5))
                                  ->first();
    
        if ($duplicatePost) {
            return redirect()->back()->with('error', 'You have already posted this reply.');
        }
    
        // Sanitize the content
        $cleanedContent = Purifier::clean($request->content);
    
        // Create the reply with sanitized content
        $reply = ForumPost::create([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'content' => $cleanedContent,
        ]);
    
        // Send a notification to the thread owner
        $notification = new ForumReplyNotification($thread, $reply);
        $thread->user->notify($notification);
    
        return redirect()->back()->with('success', 'Reply posted successfully.');
    }
    


    public function editPost(Request $request, ForumPost $post)
    {
        if (!Gate::allows('update-post', $post)) {
            return response()->json(['error' => 'You are not authorized to edit this post.'], 403);
        }
    
        $request->validate([
            'content' => 'required|string|max:5000', // You can adjust the max length as needed
        ]);
    
        $oldContent = $post->content;
    
        // Sanitize the new content before updating
        $cleanedContent = Purifier::clean($request->input('content'));
    
        // Update the post with sanitized content
        $post->update([
            'content' => $cleanedContent,
            'is_edited' => true,
        ]);
    
        // Record the edit history
        PostEditHistory::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'old_content' => $oldContent,
            'new_content' => $cleanedContent,
        ]);
    
        return response()->json(['success' => true, 'message' => 'Post updated successfully']);
    }
    
    

    public function getPostEditHistory(ForumPost $post)
    {
        // Ensure the user is authorized to view the edit history
        if (!Gate::allows('view-post', $post)) {
            return response()->json(['error' => 'You are not authorized to view this post\'s history.'], 403);
        }
    
        // Fetch the edit history for the post
        $editHistory = PostEditHistory::where('post_id', $post->id)
                                      ->with('user') // Assuming you want to show who made the edit
                                      ->orderBy('created_at', 'desc')
                                      ->get();
    
        return view('forum.post-history', compact('post', 'editHistory'));
    }
    public function showEditHistory(ForumPost $post)
    {
        if (!Gate::allows('view-post', $post)) {
            abort(403, 'You are not authorized to view this post\'s history.');
        }

        $editHistory = $post->editHistory()->with('user')->orderBy('created_at', 'desc')->get();
        return view('forum.edit-history', compact('post', 'editHistory'));
    }


    public function destroy(ForumPost $post)
    {
        if (!$this->canDeletePost($post)) {
            return response()->json(['error' => 'You are not authorized to delete this post.'], 403);
        }

        try {
            DB::transaction(function () use ($post) {
                $post->update(['deleted_at' => Carbon::now()]);
            });

            return response()->json(['message' => 'Post deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the post.'], 500);
        }
    }

    private function canDeletePost(ForumPost $post)
    {
        $user = Auth::user();
        return $user && ($user->id === $post->user_id || $user->can('delete forum posts'));
    }

    public function markAsSolution(Request $request, ForumThread $thread, ForumPost $post)
    {
        $this->authorize('update', $thread);

        // Mark the post as the solution
        $post->update(['is_solution' => true]);

        // Mark the thread as resolved
        $thread->update(['is_resolved' => true]);

        return redirect()->back()->with('success', 'Post marked as the solution.');
    }
}
