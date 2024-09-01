<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Forum\ForumThread;
use App\Models\Forum\ForumPost;
use App\Policies\ThreadPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{   


    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {   
        Blade::directive('pjax', function ($expression) {
            return "<?php \$GLOBALS['__pjaxLayout'] = $expression; ?>
                    <meta name=\"layout\" content=\"<?php echo $expression; ?>\">";
        });
        

        $this->registerPolicies();

        Gate::define('update-post', function (User $user, ForumPost $post) {
            return $user->id === $post->user_id || $user->isAdmin();
        });

        Gate::define('update-thread', function (User $user, ForumThread $thread) {
            return $user->id === $thread->user_id || $user->isAdmin();
        });

        Gate::define('view-post', function (User $user, ForumPost $post) {
            // You might want to adjust this logic based on your requirements
            return true; // Allows anyone to view post history
        });

        Gate::define('view-thread', function (User $user, ForumThread $thread) {
            // You might want to adjust this logic based on your requirements
            return true; // Allows anyone to view thread history
        });
    }
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        ForumThread::class => ThreadPolicy::class,
    ];

}
