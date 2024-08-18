<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\TwoFactorController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use App\Http\Controllers\Auth\TwoFactorAuthenticatedSessionController as CustomTwoFactorAuthenticatedSessionController;

use App\Http\Controllers\{
    ForumController, 
    NotificationController, 
    ProfileController,
    PostController,
    EventController,
    AvatarController,
    NewsController,
    HomeController,
    PageController,
};

use App\Http\Controllers\Settings\{
    SettingsController,
    ProfileController as SettingProfileController,
    SettingsSecurityController,
};

use App\Http\Controllers\Admin\{
    DashboardController,
    UserController,
    ImageController,
    ForumDashboardController,
    ForumCategoryController,
    ThreadTagController,
    NewsController as AdminNewsController,
};

// Public routes
Route::middleware('web')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/about', [PageController::class, 'about'])->name('page.about');
    Route::get('/terms', [PageController::class, 'terms'])->name('page.terms');
    Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('page.disclaimer');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('page.privacy');
    
    // News
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/search', [NewsController::class, 'search'])->name('news.search');
    Route::get('news/{slug}', [NewsController::class, 'show'])->name('news.show');

    Route::resource('events', EventController::class);
    Route::get('events/search', [EventController::class, 'search'])->name('events.search');

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
        Route::post('/two-factor-login', [CustomTwoFactorAuthenticatedSessionController::class, 'store'])->name('two-factor.login.store');
    });

    Route::middleware(['auth'])->group(function () {
        Route::prefix('/two-factor')->name('two-factor.')->group(function () {
            Route::get('/', [TwoFactorController::class, 'index'])->name('index');
            Route::get('/setup', [TwoFactorController::class, 'enableToken'])->name('setup');
            Route::post('/confirm', [TwoFactorController::class, 'confirmToken'])->name('confirm-token');
            Route::get('/recovery-codes', [TwoFactorController::class, 'showRecoveryCodes'])->name('recovery-codes-alt'); // Ensure this name is unique
            Route::post('/disable', [TwoFactorController::class, 'disable'])->name('disable-alt');
        });
    });
    
    Route::middleware(['2fa'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/two-factor-challenge', [TwoFactorController::class, 'show'])->name('two-factor.challenge');
        Route::post('/two-factor-challenge', [TwoFactorController::class, 'store'])->name('two-factor.store');
 
        Route::middleware(['auth'])->group(function () {

            Route::get('user/avatar', [AvatarController::class, 'show'])->name('user.avatar');
            Route::post('user/avatar', [AvatarController::class, 'store'])->name('user.avatar.store');

            Route::prefix('forum')->name('forum.')->group(function () {
                // Forum authenticated routes
                Route::post('/', [ForumController::class, 'store'])->name('store');
                Route::post('/{thread}/posts', [PostController::class, 'store'])->name('posts.store');
                Route::get('/search', [ForumController::class, 'search'])->name('search');
                
                Route::post('/{thread}/mark-solution/{post}', [ForumController::class, 'markAsSolution'])->name('markSolution');
                Route::post('/{thread}/mark-helpful', [ForumController::class, 'markAsHelpful'])->name('markHelpful');

                Route::get('/thread/create/', [ForumController::class, 'create'])->name('create');
                Route::get('/{thread}/edit', [ForumController::class, 'edit'])->name('threads.edit');
                Route::put('/{thread}', [ForumController::class, 'update'])->name('threads.update');

                Route::post('/posts/{post}/edit', [ForumController::class, 'editPost'])->name('post.edit');
                Route::get('/posts/{post}/history', [ForumController::class, 'getPostEditHistory'])->name('post.history');

                Route::post('/threads/{thread}/edit', [ForumController::class, 'editThread'])->name('thread.edit');
                Route::get('/threads/{thread}/history', [ForumController::class, 'getThreadEditHistory'])->name('thread.history');
                
                // Post like/unlike routes
                Route::post('/posts/{post}/like', [ForumController::class, 'likePost'])->name('post.like');
                Route::post('/posts/{post}/unlike', [ForumController::class, 'unlikePost'])->name('post.unlike');

                // Thread like/unlike routes
                Route::post('/threads/{thread}/like', [ForumController::class, 'likeThread'])->name('thread.like');
                Route::post('/threads/{thread}/unlike', [ForumController::class, 'unlikeThread'])->name('thread.unlike');
            });

            // Notifications
            Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
            Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
            Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

            // Profile
            Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

            // Settings
            Route::get('/settings', [SettingsController::class, 'settings'])->name('settings.index');
            Route::post('/settings/update-email', [SettingsController::class, 'updateEmail'])->name('settings.updateEmail');
            Route::post('/settings/update-password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');

            // Profiles
            Route::get('/settings/profile', [SettingProfileController::class, 'profile'])->name('settings.profile');
            Route::post('/settings/profile/link-habbo', [SettingProfileController::class, 'linkHabbo'])->name('settings.linkHabbo');
            Route::get('/settings/profile/check-habbo-status', [SettingProfileController::class, 'checkHabboStatus'])->name('settings.checkHabboStatus');
            Route::get('/settings/profile/motd-code', [SettingProfileController::class, 'getMotdCode'])->name('settings.getMotdCode');
            Route::post('/settings/profile/cancel-habbo-link', [SettingProfileController::class, 'cancelHabboLink'])->name('settings.cancelHabboLink');
            Route::post('/settings/profile/remove-habbo-link', [SettingProfileController::class, 'removeHabboLink'])->name('settings.removeHabboLink');
            Route::post('/settings/profile/update-mot', [SettingProfileController::class, 'updateMot'])->name('settings.updateMot');
            Route::post('/settings/profile/update-forum-signature', [SettingProfileController::class, 'updateForumSignature'])->name('settings.updateForumSignature');
            Route::post('/settings/profile/banner', [SettingProfileController::class, 'updateProfileBanner'])->name('settings.updateProfileBanner');

            // Security settings routes
            Route::prefix('settings/security')->middleware('auth')->group(function () {
                Route::get('/', [SettingsSecurityController::class, 'index'])->name('settings.security.index');
                Route::post('/update-login-alerts', [SettingsSecurityController::class, 'updateLoginAlerts'])->name('security.updateLoginAlerts');
                Route::delete('/sessions/{id}', [SettingsSecurityController::class, 'logoutSession'])->name('security.logoutSession');
                Route::delete('/trusted-devices/{id}', [SettingsSecurityController::class, 'removeTrustedDevice'])->name('security.removeTrustedDevice-alt'); // Unique name
                Route::post('/toggle-2fa', [SettingsSecurityController::class, 'toggle2FA'])->name('security.toggle2FA');
                Route::delete('/logout-session/{sessionId}', [SettingsSecurityController::class, 'logoutSession'])->name('security.logoutSession-alt'); // Unique name
                Route::post('/logout-all-sessions', [SettingsSecurityController::class, 'logoutAllSessions'])->name('settings.logoutAllSessions');
                Route::post('/two-factor/verify-password', [TwoFactorController::class, 'verifyPassword'])->name('two-factor.verify-password');
                Route::post('/two-factor/reset-recovery-codes', [TwoFactorController::class, 'resetRecoveryCodes'])->name('two-factor.reset-recovery-codes');
            });

            Route::get('/settings/notifications', [SettingsController::class, 'notifications'])->name('settings.notifications');
            Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
            Route::post('/settings/security', [SettingsController::class, 'updateSecurity'])->name('settings.updateSecurity');

            Route::post('/email/verification-notification', function (Request $request) {
                $request->user()->sendEmailVerificationNotification();
                return back()->with('message', 'Verification link sent!');
            })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

            // Logout
            Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

            // Admin routes
            Route::middleware(['can:view admin dashboard'])->prefix('admin')->name('admin.')->group(function () {
                Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
                Route::resource('/users', UserController::class);
                Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->name('admin.users.restore');

                Route::resource('/images', ImageController::class)->except(['show', 'edit', 'update']);
                Route::delete('images/bulk-delete', [ImageController::class, 'bulkDelete'])->name('images.bulkDelete');
                Route::resource('/news', AdminNewsController::class);
                Route::post('/news/preview', [AdminNewsController::class, 'preview'])->name('news.preview');

                Route::get('/forum', [ForumDashboardController::class, 'index'])->name('admin.forum.dashboard');
                Route::resource('/forum/forum-categories', ForumCategoryController::class);
                Route::get('/forum/forum-tags', [ThreadTagController::class, 'index'])->name('forum-tags.index');
                Route::get('/forum/forum-tags/create', [ThreadTagController::class, 'create'])->name('forum-tags.create');
                Route::post('/forum/forum-tags', [ThreadTagController::class, 'store'])->name('forum-tags.store');
                Route::get('/forum/forum-tags/{id}/edit', [ThreadTagController::class, 'edit'])->name('forum-tags.edit');
                Route::put('/forum/forum-tags/{id}', [ThreadTagController::class, 'update'])->name('forum-tags.update');
                Route::delete('/forum/forum-tags/{id}', [ThreadTagController::class, 'destroy'])->name('forum-tags.destroy');
            });
        });
    });
});
