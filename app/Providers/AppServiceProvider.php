<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination.sederhana');

        // Di Railway aplikasi berjalan di belakang proxy HTTPS.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
