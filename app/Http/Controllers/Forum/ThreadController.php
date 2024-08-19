<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ThreadTag;
use App\Models\Forum\ForumCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Traits\PjaxTrait;

class ThreadController extends Controller
{   
    use PjaxTrait;

    public function index()
    {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('index')],
            ['label' => 'Forum', 'url' => route('forum.index')],
        ];

        $threads = $this->getThreadsQuery()->paginate(10);
        $tags = ThreadTag::all();
        $recentActivities = $this->getRecentActivities();
        $topContributors = $this->getTopContributors();
        $categories = ForumCategory::all();

        $hideCategories = false;

        return $this->view('forum.index', compact('threads', 'tags', 'recentActivities', 'topContributors', 'categories', 'breadcrumbs', 'hideCategories'));
    }

    public function show($slug)
    {
        $thread = ForumThread::where('slug', $slug)
            ->with([
                'user.roles',
                'category',
                'tags',
                'posts.user.roles',
                'posts.user' => function ($query) {
                    $query->withCount('forumPosts');
                },
                'posts.likes',
                'likes'
            ])
            ->firstOrFail();

        $userHasLikedThread = $thread->likes()->where('user_id', auth()->id())->exists();

        $postsWithUserLikes = $thread->posts->map(function ($post) {
            $post->userHasLiked = $post->likes()->where('user_id', auth()->id())->exists();
            return $post;
        });

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('index')],
            ['label' => 'Forum', 'url' => route('forum.index')],
            ['label' => $thread->category->name, 'url' => route('forum.category', $thread->category->slug)],
            ['label' => $thread->title, 'url' => route('forum.show', $thread->slug)],
        ];

        $thread->increment('view_count');

         return $this->view('forum.show', compact('thread', 'postsWithUserLikes', 'breadcrumbs', 'userHasLikedThread'));
    }

    public function create()
    {
        Log::info('ThreadController@create method was called.');

        $categories = ForumCategory::all();
        $tags = ThreadTag::all();

        Log::info('Categories retrieved:', ['categories' => $categories]);
        Log::info('Tags retrieved:', ['tags' => $tags]);

         return $this->view('forum.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:forum_categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:thread_tags,id',
        ]);

        $thread = ForumThread::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'is_sticky' => $request->has('is_sticky'),
        ]);

        if ($request->has('tags')) {
            $thread->tags()->attach($request->tags);
        }

        return redirect()->route('forum.show', $thread->slug)->with('success', 'Thread created successfully.');
    }

    public function edit(ForumThread $thread)
    {
        $this->authorize('update', $thread);

        return view('forum.edit', compact('thread'));
    }

    public function update(Request $request, ForumThread $thread)
    {
        $this->authorize('update', $thread);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $thread->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('forum.show', $thread->slug)->with('success', 'Thread updated successfully.');
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3',
        ]);

        $query = $request->input('query');

        $threads = $this->getThreadsQuery()
            ->where(function ($q) use ($query) {
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
                return (object)[
                    'user' => $thread->user,
                    'action' => 'created thread "' . Str::limit($thread->title, 30) . '"',
                    'created_at' => $thread->created_at,
                ];
            });
    }
}
