<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ForumPost;
use Illuminate\Http\Request;

class SolutionController extends Controller
{
    public function markAsSolution(Request $request, ForumThread $thread, ForumPost $post)
    {
        if ($request->user()->id !== $thread->user_id) {
            return back()->with('error', 'Only the thread owner can mark a solution.');
        }

        $post->update(['is_solution' => true]);
        $thread->markAsResolved();

        if ($thread->helpTags()->exists()) {
            $post->user->incrementContributionPoints(10);
        }

        return back()->with('success', 'Solution marked successfully and thread resolved.');
    }
}
