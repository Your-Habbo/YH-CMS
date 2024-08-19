<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumCategory;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ThreadTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Http\Traits\PjaxTrait;

class CategoryController extends Controller
{
    use PjaxTrait;

    public function show($slug)
    {
        $category = ForumCategory::where('slug', $slug)->firstOrFail();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('index')],
            ['label' => 'Forum', 'url' => route('forum.index')],
            ['label' => $category->name, 'url' => route('forum.category', $category->slug)],
        ];

        $threads = ForumThread::where('category_id', $category->id)->paginate(10);
        $tags = ThreadTag::all();
        $recentActivities = $this->getRecentActivities();
        $topContributors = $this->getTopContributors();
        $categories = ForumCategory::all();

        $hideCategories = true;

        return $this->view('forum.index', compact('threads', 'tags', 'category', 'categories', 'recentActivities', 'topContributors', 'breadcrumbs', 'hideCategories'));
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
