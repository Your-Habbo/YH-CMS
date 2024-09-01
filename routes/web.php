<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;


use App\Http\Controllers\{
    NotificationController, 
    ProfileController,
    EventController,
    NewsController,
    HomeController,
    PageController,
    TwoFactorController,
};

use App\Http\Controllers\Auth\{
    TwoFactorAuthenticatedSessionController as CustomTwoFactorAuthenticatedSessionController,

};

use App\Http\Controllers\Forum\{
    ThreadController,
    CategoryController as ForumCategoryController,
    TagController as ForumTagController,
    PostController as ForumPostController,
    SearchController,
    SolutionController,
    HelpfulController,
    LikeController
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
    ForumCategoryController as AdminForumCategoryController,
    ThreadTagController as AdminThreadTagController,
    NewsController as AdminNewsController,
    RoleController as AdminRoleController,
    PageController as AdminPageController,
};

use App\Http\Controllers\Helper\{
    SongSearchController,
    AvatarController,

};
// Public routes
Route::middleware('web')->group(function () {
    Route::get('/habbo-imaging/avatarimage', function () {
        $params = request()->all();
        $habboUrl = "https://www.habbo.com.tr/habbo-imaging/avatarimage?" . http_build_query($params);
        $response = Http::get($habboUrl);
        return response($response->body(), $response->status())
            ->header('Content-Type', $response->header('Content-Type'));
    });

    Route::get('/search-song', [SongSearchController::class, 'searchSong']);

    Route::get('/pages/{slug}', [AdminPageController::class, 'show'])->name('pages.show');

    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('/about', [PageController::class, 'about'])->name('page.about');
    Route::get('/terms', [PageController::class, 'terms'])->name('page.terms');
    Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('page.disclaimer');
    Route::get('/privacy', [PageController::class, 'privacy'])->name('page.privacy');
    Route::get('pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    
    // News
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/search', [NewsController::class, 'search'])->name('news.search');
    Route::get('news/{slug}', [NewsController::class, 'show'])->name('news.show');

    Route::resource('events', EventController::class);
    Route::get('events/search', [EventController::class, 'search'])->name('events.search');

    // Forum
    Route::get('/forum', [ThreadController::class, 'index'])->name('forum.index');
    Route::get('/forum/category/{slug}', [ForumCategoryController::class, 'show'])->name('forum.category');
    Route::get('/forum/{slug}', [ThreadController::class, 'show'])->name('forum.show');
    Route::get('/forum/tag/{slug}', [ForumTagController::class, 'show'])->name('forum.tag');
    Route::get('/forum/search', [SearchController::class, 'search'])->name('forum.search');



    //Guest Routes
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
            Route::get('/recovery-codes', [TwoFactorController::class, 'showRecoveryCodes'])->name('recovery-codes-alt');
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
                Route::post('/', [ThreadController::class, 'store'])->name('store');
                Route::post('/{thread}/posts', [ForumPostController::class, 'reply'])->name('posts.store');
            
                Route::post('/{thread}/mark-solution/{post}', [SolutionController::class, 'markAsSolution'])->name('markSolution');
                Route::post('/{thread}/mark-helpful', [HelpfulController::class, 'markAsHelpful'])->name('markHelpful');
            
                // Thread creation and editing
                Route::get('/create', [ThreadController::class, 'create'])->name('create')->middleware('throttle:5,1');
                Route::get('/{thread}/edit', [ThreadController::class, 'edit'])->name('threads.edit');  
                Route::put('/{thread}', [ThreadController::class, 'update'])->name('threads.update')->middleware('throttle:5,1');
                Route::post('/threads/{thread}/solve/{post}', [ThreadController::class, 'markAsSolved'])->name('threads.solve');

                // Post editing and history 
                Route::post('/posts/{post}/edit', [ForumPostController::class, 'editPost'])->name('posts.edit');
                Route::get('/posts/{post}/history', [ForumPostController::class, 'getPostEditHistory'])->name('post.history');
            
                // Thread history
                Route::get('/{thread}/history', [ThreadController::class, 'getThreadEditHistory'])->name('thread.history');
            
                // Post like/unlike routes
                Route::post('/posts/{post}/like', [LikeController::class, 'likePost'])->name('post.like')->middleware('throttle:150,1');
                Route::post('/posts/{post}/unlike', [LikeController::class, 'unlikePost'])->name('post.unlike')->middleware('throttle:150,1');

                // Thread like/unlike routes
                Route::post('/{thread}/like', [LikeController::class, 'likeThread'])->name('thread.like')->middleware('throttle:150,1');
                Route::post('/{thread}/unlike', [LikeController::class, 'unlikeThread'])->name('thread.unlike')->middleware('throttle:150,1');

                 // Soft delete routes
                Route::delete('/threads/{thread}', [ThreadController::class, 'delete'])->name('threads.destroy');
                Route::delete('/posts/{post}', [ForumPostController::class, 'destroy'])->name('posts.destroy');

                // Mark as solution route
                Route::post('/threads/{thread}/posts/{post}/mark-solution', [ForumPostController::class, 'markAsSolution'])->name('posts.markSolution');
                Route::post('/threads/{thread}/toggle-sticky', [ThreadController::class, 'toggleSticky'])->name('threads.toggleSticky');
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
                Route::delete('/trusted-devices/{id}', [SettingsSecurityController::class, 'removeTrustedDevice'])->name('security.removeTrustedDevice-alt');
                Route::post('/toggle-2fa', [SettingsSecurityController::class, 'toggle2FA'])->name('security.toggle2FA');
                Route::delete('/logout-session/{sessionId}', [SettingsSecurityController::class, 'logoutSession'])->name('security.logoutSession-alt');
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
                Route::resource('/forum/forum-categories', AdminForumCategoryController::class);
                Route::get('/forum/forum-tags', [AdminThreadTagController::class, 'index'])->name('forum-tags.index');
                Route::get('/forum/forum-tags/create', [AdminThreadTagController::class, 'create'])->name('forum-tags.create');
                Route::post('/forum/forum-tags', [AdminThreadTagController::class, 'store'])->name('forum-tags.store');
                Route::get('/forum/forum-tags/{id}/edit', [AdminThreadTagController::class, 'edit'])->name('forum-tags.edit');
                Route::put('/forum/forum-tags/{id}', [AdminThreadTagController::class, 'update'])->name('forum-tags.update');
                Route::delete('/forum/forum-tags/{id}', [AdminThreadTagController::class, 'destroy'])->name('forum-tags.destroy');



                Route::get('/roles', [AdminRoleController::class, 'index'])->name('roles.index');
                Route::get('/roles/create', [AdminRoleController::class, 'create'])->name('roles.create');
                Route::post('/roles', [AdminRoleController::class, 'store'])->name('roles.store');
                Route::get('/roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('roles.edit');
                Route::put('/roles/{role}', [AdminRoleController::class, 'update'])->name('roles.update');
                Route::delete('/roles/{role}', [AdminRoleController::class, 'destroy'])->name('roles.destroy');



                
                Route::get('pages', [AdminPageController::class, 'index'])->name('pages.index');
                Route::get('pages/create', [AdminPageController::class, 'create'])->name('pages.create');
                Route::post('pages', [AdminPageController::class, 'store'])->name('pages.store');
                Route::get('pages/{page}', [AdminPageController::class, 'show'])->name('pages.show');
                Route::get('pages/{page}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
                Route::put('pages/{page}', [AdminPageController::class, 'update'])->name('pages.update');
                Route::delete('pages/{page}', [AdminPageController::class, 'destroy'])->name('pages.destroy');
                
            });
        });
    });
});
