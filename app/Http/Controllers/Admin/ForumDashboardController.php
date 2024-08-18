<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Forum\ForumPost;
use App\Models\Forum\ForumCategory;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ThreadLike;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ForumDashboardController extends Controller
{
    public function index()
    {
        // Top Widgets Data
        $postsToday = ForumPost::whereDate('created_at', Carbon::today())->count();
        $postsThisWeek = ForumPost::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $postsThisMonth = ForumPost::whereMonth('created_at', Carbon::now()->month)->count();

        // Daily Post Trends (Last 30 Days)
        $dailyPosts = ForumPost::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        // Hourly Post Trends (Across All Time)
        $hourlyPosts = ForumPost::select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as count'))
            ->groupBy('hour')
            ->orderBy('hour', 'asc')
            ->get()
            ->pluck('count', 'hour');

        // Threads created per day (Last 30 Days)
        $threadsPerDay = ForumThread::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        // Posts created per day (Last 30 Days)
        $postsPerDay = ForumPost::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        // Likes received per day (Last 30 Days)
        $likesPerDay = ThreadLike::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        // Daily active users (Last 30 Days)
        $activeUsersPerDay = ForumPost::select(DB::raw('DATE(created_at) as date'), DB::raw('count(distinct user_id) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date');

        // Category Activity (Most Active Categories)
        $categoryActivity = ForumCategory::with(['threads', 'threads.posts'])
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'thread_count' => $category->threads->count(),
                    'post_count' => $category->threads->sum(function ($thread) {
                        return $thread->posts->count();
                    })
                ];
            });

        return view('admin.forum.index', compact(
            'postsToday',
            'postsThisWeek',
            'postsThisMonth',
            'dailyPosts',
            'hourlyPosts',
            'threadsPerDay',
            'postsPerDay',
            'likesPerDay',
            'activeUsersPerDay',
            'categoryActivity'
        ));
    }
}

