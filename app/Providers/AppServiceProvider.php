<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
            $announcements=DB::table('announcements')->where(fn($q)=>$q->whereNull('target_role')->orWhere('target_role',auth()->user()->role))->select('title','content','published_at');
            $personal=Schema::hasTable('user_notifications')?DB::table('user_notifications')->where('user_id',auth()->id())->select('title','message as content','created_at as published_at'):null;
            $view->with('navNotifications',$personal?$personal->unionAll($announcements)->orderByDesc('published_at')->limit(8)->get():$announcements->orderByDesc('published_at')->limit(8)->get());
        });
    }
}
