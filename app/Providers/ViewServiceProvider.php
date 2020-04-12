<?php

namespace App\Providers;

use App\Composers\GlobalComposer;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Add GlobalComposerto all views
        view()->composer('*', GlobalComposer::class);
    }
}
