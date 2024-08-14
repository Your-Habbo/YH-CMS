<?php

// app/Providers/AvatarServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AvatarGenerator;

class AvatarServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AvatarGenerator::class, function ($app) {
            return new AvatarGenerator();
        });
    }
}
