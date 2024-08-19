<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumThread;
use Illuminate\Http\Request;

class HelpfulController extends Controller
{
    public function markAsHelpful(Request $request, ForumThread $thread)
    {
        $thread->update(['is_helpful' => true]);
        $thread->user->incrementContributionPoints(20);

        return back()->with('success', 'Guide marked as helpful.');
    }
}
