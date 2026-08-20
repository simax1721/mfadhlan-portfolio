<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Belt-and-suspenders alongside trustProxies() in bootstrap/app.php:
        // force every generated URL (assets, routes) to https in production,
        // regardless of how the request's scheme was detected.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
