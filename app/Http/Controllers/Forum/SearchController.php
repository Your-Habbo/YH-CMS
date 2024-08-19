<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Http\Traits\PjaxTrait;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ThreadTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    use PjaxTrait;

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = $request->input('query');

        $threads = $this->getThreadsQuery()
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhereHas('user', function ($subQ) use ($query) {
                      $subQ->where('name', 'like', "%{$query}%");
                  })
                  ->orWhereHas('category', function ($subQ) use ($query) {
                      $subQ->where('name', 'like', "%{$query}%");
                  })
                  ->orWhereHas('tags', function ($subQ) use ($query) {
                      $subQ->where('name', 'like', "%{$query}%");
                  });
            })
            ->paginate(15);

        $tags = ThreadTag::all();
        $recentActivities = $this->getRecentActivities();
        $topContributors = $this->getTopContributors();

        return $this->view('forum.index', compact('threads', 'tags', 'query', 'recentActivities', 'topContributors'));
    }

    private function getThreadsQuery()
    {
        return ForumThread::with('user', 'category', 'tags', 'posts')
            ->withCount('posts')
            ->orderBy('is_sticky', 'desc')
            ->orderBy('created_at', 'desc');
    }

    private function getTopContributors()
    {
        return User::orderBy('contribution_points', 'desc')
                   ->take(5)
                   ->get();
    }

    private function getRecentActivities()
    {
        return ForumThread::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($thread) {
                return (object) [
                    'user' => $thread->user,
                    'action' => 'created thread "' . Str::limit($thread->title, 30) . '"',
                    'created_at' => $thread->created_at,
                ];
            });
    }
}