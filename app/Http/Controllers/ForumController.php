<?php

namespace App\Http\Controllers;

use App\Http\Traits\PjaxTrait;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ForumCategory;
use App\Models\Forum\ThreadTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;
use App\Notifications\ForumReplyNotification;
use App\Services\NotificationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ForumController extends Controller
{   
    use PjaxTrait;
    use AuthorizesRequests;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display a listing of the forum threads.
     */
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

        return $this->view('forum.index', compact('threads', 'tags', 'recentActivities', 'topContributors', 'categories', 'breadcrumbs'));
    }

    /**
     * Display the specified thread.
     */
    public function show($slug)
    {
        $thread = ForumThread::where('slug', $slug)->with('user', 'category', 'tags', 'posts.user')->firstOrFail();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('index')],
            ['label' => 'Forum', 'url' => route('forum.index')],
            ['label' => $thread->category->name, 'url' => route('forum.category', $thread->category->slug)],
            ['label' => $thread->title, 'url' => route('forum.show', $thread->slug)],
        ];

        $thread->increment('view_count');

        return $this->view('forum.show', compact('thread', 'breadcrumbs'));
    }

    /**
     * Display threads filtered by a specific tag.
     */
    public function tag($slug)
    {
        $tag = ThreadTag::where('slug', $slug)->firstOrFail();
        $threads = $this->getThreadsQuery()->whereHas('tags', function($query) use ($tag) {
            $query->where('thread_tags.id', $tag->id);
        })->paginate(10);

        $tags = ThreadTag::all();
        $recentActivities = $this->getRecentActivities();
        $topContributors = $this->getTopContributors();

        return $this->view('forum.index', compact('threads', 'tags', 'recentActivities', 'topContributors', 'tag'));
    }

    /**
     * Display threads for a specific category.
     */
    public function category($slug)
    {
        $category = ForumCategory::where('slug', $slug)->firstOrFail();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('index')],
            ['label' => 'Forum', 'url' => route('forum.index')],
            ['label' => $category->name, 'url' => route('forum.category', $category->slug)],
        ];

        $threads = $this->getThreadsQuery()->where('category_id', $category->id)->paginate(10);
        $tags = ThreadTag::all();
        $recentActivities = $this->getRecentActivities();
        $topContributors = $this->getTopContributors();
        $categories = ForumCategory::all();

        return $this->view('forum.index', compact('threads', 'tags', 'category', 'categories', 'recentActivities', 'topContributors', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new thread.
     */
    public function create()
    {
        $categories = ForumCategory::all();
        $tags = ThreadTag::all();
        return $this->view('forum.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created thread in the database.
     */
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

    /**
     * Search for threads based on a query string.
     */
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

    /**
     * Show the form for editing the specified thread.
     */
    public function edit(ForumThread $thread)
    {
        $this->authorize('update', $thread);

        return $this->view('forum.edit', compact('thread'));
    }

    /**
     * Update the specified thread in the database.
     */
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

    /**
     * Mark a post as a solution.
     */
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

    /**
     * Mark a guide as helpful.
     */
    public function markAsHelpful(Request $request, ForumThread $thread)
    {
        $thread->update(['is_helpful' => true]);
        $thread->user->incrementContributionPoints(20);

        return back()->with('success', 'Guide marked as helpful.');
    }

    /**
     * Reply to a thread.
     */
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

    /**
     * Edit a post in a thread.
     */
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

    /**
     * Edit a thread.
     */
    public function editThread(Request $request, ForumThread $thread)
    {
        if (!Gate::allows('update-thread', $thread)) {
            return response()->json(['error' => 'You are not authorized to edit this thread.'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $oldTitle = $thread->title;
        $oldContent = $thread->content;

        $thread->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_edited' => true,
        ]);

        ThreadEditHistory::create([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
            'old_title' => $oldTitle,
            'new_title' => $request->title,
            'old_content' => $oldContent,
            'new_content' => $request->content,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get the post edit history.
     */
    public function getPostEditHistory(ForumPost $post)
    {
        if (!Gate::allows('view-post', $post)) {
            return response()->json(['error' => 'You are not authorized to view this post\'s history.'], 403);
        }

        $history = $post->editHistory()->with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($history);
    }

    /**
     * Get the thread edit history.
     */
    public function getThreadEditHistory(ForumThread $thread)
    {
        if (!Gate::allows('view-thread', $thread)) {
            return response()->json(['error' => 'You are not authorized to view this thread\'s history.'], 403);
        }

        $history = $thread->editHistory()->with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($history);
    }

    /**
     * Show the edit history of a post.
     */
    public function showEditHistory(ForumPost $post)
    {
        if (!Gate::allows('view-post', $post)) {
            abort(403, 'You are not authorized to view this post\'s history.');
        }

        $editHistory = $post->editHistory()->with('user')->orderBy('created_at', 'desc')->get();
        return $this->view('forum.edit-history', compact('post', 'editHistory'));
    }

    /**
     * Like a thread.
     */
    public function likeThread(ForumThread $thread)
    {
        $thread->increment('likes_count');
        return response()->json(['likes_count' => $thread->likes_count]);
    }

    /**
     * Like a post.
     */
    public function likePost(ForumPost $post)
    {
        $post->increment('likes_count');
        return response()->json(['likes_count' => $post->likes_count]);
    }

    /**
     * Unlike a post.
     */
    public function unlikePost(ForumPost $post)
    {
        if ($post->likes_count > 0) {
            $post->decrement('likes_count');
        }
        return response()->json(['likes_count' => $post->likes_count]);
    }

    /**
     * Unlike a thread.
     */
    public function unlikeThread(ForumThread $thread)
    {
        if ($thread->likes_count > 0) {
            $thread->decrement('likes_count');
        }
        return response()->json(['likes_count' => $thread->likes_count]);
    }

    /**
     * Get the query for retrieving threads.
     */
    private function getThreadsQuery()
    {
        return ForumThread::with('user', 'category', 'tags', 'posts')
            ->withCount('posts')
            ->orderBy('is_sticky', 'desc')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the top contributors in the forum.
     */
    private function getTopContributors()
    {
        return User::orderBy('contribution_points', 'desc')
                   ->take(5)
                   ->get();
    }

    /**
     * Get the recent activities in the forum.
     */
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
