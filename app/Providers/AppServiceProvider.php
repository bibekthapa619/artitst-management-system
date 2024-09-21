<?php

namespace App\Providers;

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
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
        Route::aliasMiddleware('role', RoleMiddleware::class);

        Blade::if('hasrole', function ($roles) {
            $user = auth()->user();
            if (!is_array($roles)) {
                $roles = explode('|', $roles);
            }
    
            return $user && in_array($user->role, $roles);
        });
    }
}
