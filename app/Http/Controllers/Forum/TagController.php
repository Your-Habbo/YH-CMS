<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Http\Traits\PjaxTrait;
use App\Models\Forum\ThreadTag;
use App\Models\Forum\ForumThread;
use App\Models\User;
use Illuminate\Support\Str;

class TagController extends Controller
{
    use PjaxTrait;

    public function show($slug)
    {
        $tag = ThreadTag::where('slug', $slug)->firstOrFail();
        $threads = $this->getThreadsQuery()->whereHas('tags', function($query) use ($tag) {
            $query->where('thread_tags.id', $tag->id);
        })->paginate(10);
    
        $tags = ThreadTag::all();
        $recentActivities = $this->getRecentActivities();
        $topContributors = $this->getTopContributors();
    
        // Define breadcrumbs
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('index')],
            ['label' => 'Forum', 'url' => route('forum.index')],
            ['label' => 'Tag: ' . $tag->name, 'url' => route('forum.tag', $tag->slug)],
        ];  

        $hideCategories = true;
    
        return $this->view('forum.index', compact('threads', 'tags', 'recentActivities', 'topContributors', 'tag', 'breadcrumbs', 'hideCategories'));
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
