<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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
        View::composer('layouts.app', function ($view) {
            if (!auth()->check()) return;
            $view->with('navNotifications', DB::table('announcements')->where(fn($q)=>$q->whereNull('target_role')->orWhere('target_role',auth()->user()->role))->latest('published_at')->limit(5)->get());
        });
    }
}
