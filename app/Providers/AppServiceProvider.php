<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Inertia::share([
            'auth' => function () {
                $user = Auth::user();

                return [
                    'user' => $user,
                    'roles' => $user 
                        ? $user->getRoleNames()->toArray() 
                        : [],
                    'permissions' => $user 
                        ? $user->getAllPermissions()->pluck('name')->toArray() 
                        : [],
                ];
            },
        ]);
    }
}