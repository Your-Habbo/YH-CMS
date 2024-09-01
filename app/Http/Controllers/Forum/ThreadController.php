<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ThreadTag;
use App\Models\Forum\ForumCategory;
use App\Models\Forum\ThreadEditHistory;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Traits\PjaxTrait;
use Purifier;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class ThreadController extends Controller
{   
    use PjaxTrait;
    use AuthorizesRequests;

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
    
        // Cache key with a combination of thread ID and user IP or ID
        $cacheKey = 'thread_viewed_' . $thread->id . '_' . request()->ip();
        if (!Cache::has($cacheKey)) {
            // Increment the view count
            $thread->increment('view_count');
            // Store the cache entry for 15 minutes
            Cache::put($cacheKey, true, now()->addMinutes(15));
        }
    
        // Manual pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $postsWithUserLikes->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedPosts = new LengthAwarePaginator(
            $currentPageItems,
            $postsWithUserLikes->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('index')],
            ['label' => 'Forum', 'url' => route('forum.index')],
            ['label' => $thread->category->name, 'url' => route('forum.category', $thread->category->slug)],
            ['label' => $thread->title, 'url' => route('forum.show', $thread->slug)],
        ];
    
        return $this->view('forum.show', compact('thread', 'paginatedPosts', 'breadcrumbs', 'userHasLikedThread'));
    }   

    public function toggleSticky(ForumThread $thread)
    {
        $user = Auth::user();
        
        Log::info('Toggle Sticky attempted', [
            'user_id' => $user ? $user->id : 'Not authenticated',
            'thread_id' => $thread->id,
            'user_roles' => $user ? $user->roles->pluck('name') : 'N/A',
            'user_permissions' => $user ? $user->getAllPermissions()->pluck('name') : 'N/A',
        ]);

        if (!$user) {
            Log::warning('Toggle Sticky failed: User not authenticated');
            return response()->json(['error' => 'User is not authenticated.'], 401);
        }

        if (!$user->can('manage forum')) {
            Log::warning('Toggle Sticky failed: User does not have permission', [
                'user_id' => $user->id,
                'required_permission' => 'manage forum',
            ]);
            return response()->json(['error' => 'User does not have permission to manage forum threads.'], 403);
        }

        try {
            $thread->update(['is_sticky' => !$thread->is_sticky]);
            Log::info('Toggle Sticky successful', [
                'thread_id' => $thread->id,
                'new_sticky_status' => $thread->is_sticky,
            ]);

            return response()->json([
                'message' => $thread->is_sticky ? 'Thread marked as sticky.' : 'Thread unmarked as sticky.',
                'is_sticky' => $thread->is_sticky
            ]);
        } catch (\Exception $e) {
            Log::error('Toggle Sticky failed: Exception occurred', [
                'thread_id' => $thread->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'An error occurred while updating the thread: ' . $e->getMessage()], 500);
        }
    }


    
    public function indexDeleted()
    {
        $this->authorize('see deleted threads');

        $deletedThreads = ForumThread::onlyDeleted()->paginate(10);

        return view('forum.deleted', compact('deletedThreads'));
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
    
        $cleanedTitle = strip_tags($request->input('title')); 

        $cleanedContent = Purifier::clean($request->content, 'default'); // Use 'default' config
    
        $thread = ForumThread::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $cleanedTitle,
            'slug' => Str::slug($cleanedTitle),
            'content' => $cleanedContent,
            'is_sticky' => $request->has('is_sticky'),
        ]);
    
        if ($request->has('tags')) {
            $thread->tags()->attach($request->tags);
        }
    
        return redirect()->route('forum.show', $thread->slug)->with('success', 'Thread created successfully.');
    }
    

    // Show the edit form
    public function edit(ForumThread $thread)
    {   

        Log::info('ThreadController@edit method was called.');
        // Ensure the user is authorized to edit the thread
        $this->authorize('update', $thread);

        // Get all categories and tags for the form
        $categories = ForumCategory::all();
        $tags = ThreadTag::all();

        return $this->view('forum.edit', compact('thread', 'categories', 'tags'));
    }

    public function update(Request $request, ForumThread $thread)
    {
        $this->authorize('update', $thread);
    
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
    
        // Capture the old title and content before updating
        $oldTitle = $thread->title;
        $oldContent = $thread->content;
    
        // Sanitize the title and content
        $cleanedTitle = strip_tags($request->input('title')); 
        $cleanedContent = Purifier::clean($request->content, 'default');
    
        // Update the thread with sanitized data
        $thread->update([
            'title' => $cleanedTitle,
            'content' => $cleanedContent,
        ]);
    
        // Save the edit history
        ThreadEditHistory::create([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
            'old_title' => $oldTitle,
            'new_title' => $cleanedTitle,
            'old_content' => $oldContent,
            'new_content' => $cleanedContent,
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

    public function getThreadEditHistory(ForumThread $thread)
    {
        // Check if the user is authorized to view the thread's history
        if (!Gate::allows('view-thread', $thread)) {
            return response()->json(['error' => 'You are not authorized to view this thread\'s history.'], 403);
        }

        // Fetch the edit history with the associated user data, ordered by the most recent edits
        $history = $thread->editHistory()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Return the history data in a JSON response
        return response()->json($history);
    }

    public function delete(ForumThread $thread)
    {
        $user = Auth::user();

        // Check if the user is the thread creator or has the permission to delete forum threads
        if ($user->id !== $thread->user_id && !$user->hasPermissionTo('delete forum threads')) {
            return response()->json(['error' => 'You are not authorized to delete this thread.'], 403);
        }

        try {
            DB::transaction(function () use ($thread) {
                $thread->update(['deleted_at' => Carbon::now()]);
                $thread->posts()->update(['deleted_at' => Carbon::now()]);
            });

            return response()->json(['message' => 'Thread deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the thread.'], 500);
        }
    }

    public function markAsSolved(Request $request, ForumThread $thread, ForumPost $post)
    {
        // Check if the user is authorized to mark the thread as solved
        if (Auth::id() !== $thread->user_id && !Auth::user()->can('manage forum threads')) {
            return response()->json(['error' => 'You are not authorized to mark this thread as solved.'], 403);
        }

        // Check if the post belongs to the thread
        if ($post->thread_id !== $thread->id) {
            return response()->json(['error' => 'The selected post does not belong to this thread.'], 400);
        }

        $thread->update([
            'is_resolved' => true
        ]);

        $post->update([
            'is_solution' => true
        ]);

        return response()->json([
            'message' => 'Thread marked as solved.',
            'thread_id' => $thread->id,
            'solution_post_id' => $post->id
        ]);
    }
    

}
