<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwoFactorController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\DashboardController;

// Public routes
Route::middleware('web')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/terms', [PageController::class, 'terms'])->name('terms');
    Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
    
    // News
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/search', [NewsController::class, 'search'])->name('news.search');
    Route::get('news/{slug}', [NewsController::class, 'show'])->name('news.show');

    // Forum
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::get('/forum/category/{slug}', [ForumController::class, 'category'])->name('forum.category');
    Route::get('/forum/{slug}', [ForumController::class, 'show'])->name('forum.show');
    Route::get('/forum/tag/{slug}', [ForumController::class, 'tag'])->name('forum.tag');
    Route::get('/habbo-imaging/avatarimage', function () {
        $params = request()->all();
        $habboUrl = "https://www.habbo.com.tr/habbo-imaging/avatarimage?" . http_build_query($params);
        $response = Http::get($habboUrl);
        return response($response->body(), $response->status())
            ->header('Content-Type', $response->header('Content-Type'));
    });

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
        Route::get('/two-factor-login', [TwoFactorAuthenticatedSessionController::class, 'create'])->name('two-factor.login');
        Route::post('/two-factor-login', [TwoFactorAuthenticatedSessionController::class, 'store'])->name('two-factor.login.store');
    });

    Route::middleware(['auth', 'verified', '2fa'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::prefix('user/two-factor')->name('two-factor.')->group(function () {
            Route::get('/', [TwoFactorController::class, 'index'])->name('index');
            Route::get('/choose', [TwoFactorController::class, 'choose'])->name('choose');
            Route::match(['get', 'post'], '/token', [TwoFactorController::class, 'enableToken'])->name('enable-token');
            Route::post('/token/confirm', [TwoFactorController::class, 'confirmToken'])->name('confirm-token');
            Route::get('/recovery-codes', [TwoFactorController::class, 'showRecoveryCodes'])->name('recovery-codes');
            Route::delete('/disable', [TwoFactorController::class, 'disable'])->name('disable');
        });

        Route::get('/two-factor-challenge', [TwoFactorController::class, 'show'])->name('two-factor.challenge');
        Route::post('/two-factor-challenge', [TwoFactorController::class, 'store'])->name('two-factor.store');
    });

    Route::middleware(['auth'])->group(function () {
        // Forum authenticated routes
        Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
        Route::post('/forum/{thread}/posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('user/avatar', [AvatarController::class, 'show'])->name('user.avatar');
        Route::post('user/avatar', [AvatarController::class, 'store'])->name('user.avatar.store');
        Route::get('/forum/search', [ForumController::class, 'search'])->name('forum.search');
        Route::post('/forum/{thread}/mark-solution/{post}', [ForumController::class, 'markAsSolution'])->name('forum.markSolution');
        Route::post('/forum/{thread}/mark-helpful', [ForumController::class, 'markAsHelpful'])->name('forum.markHelpful');
        Route::get('/forum/create', [ForumController::class, 'create'])->name('forum.create');
        Route::get('/forum/{thread}/edit', [ForumController::class, 'edit'])->name('threads.edit');
        Route::put('/forum/{thread}', [ForumController::class, 'update'])->name('threads.update');
        Route::post('/forum/posts/{post}/edit', [ForumController::class, 'editPost'])->name('forum.post.edit');
        Route::get('/forum/posts/{post}/history', [ForumController::class, 'getPostEditHistory'])->name('forum.post.history');
        Route::post('/forum/threads/{thread}/edit', [ForumController::class, 'editThread'])->name('forum.thread.edit');
        Route::get('/forum/threads/{thread}/history', [ForumController::class, 'getThreadEditHistory'])->name('forum.thread.history');
        Route::post('/threads/{thread}/like', [ForumController::class, 'likeThread'])->name('threads.like');
        Route::post('/posts/{post}/like', [ForumController::class, 'likePost'])->name('posts.like');
        Route::post('/posts/{post}/unlike', [ForumController::class, 'unlikePost'])->name('posts.unlike');
        Route::post('/threads/{thread}/unlike', [ForumController::class, 'unlikeThread'])->name('threads.unlike');

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
        Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

        // Profile
        Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

        // Settings
        Route::get('/settings', [SettingsController::class, 'settings'])->name('settings.index');

        // Logout
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    });

    // Admin routes
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Additional admin routes can be added here.
    });
});
