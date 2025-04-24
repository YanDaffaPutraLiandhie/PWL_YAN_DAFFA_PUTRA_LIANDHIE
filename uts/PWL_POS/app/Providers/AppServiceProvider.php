<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot()
    {
        // Ini akan membuat $authUser tersedia di semua view
        View::composer('*', function ($view) {
            $view->with('authUser', Auth::user());
        });
    }
    /**
     * Bootstrap any application services.
     */
}
