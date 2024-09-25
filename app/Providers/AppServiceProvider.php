<?php

namespace App\Providers;

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
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
            $user = Auth::user();
            if (!is_array($roles)) {
                $roles = explode('|', $roles);
            }
    
            return $user && in_array($user->role, $roles);
        });

        Gate::define('can-manage-user', function ($authenticatedUser, $user) {
            return $authenticatedUser['id'] == $user['super_admin_id'];
        });

        Gate::define('can-view-artist', function ($authenticatedUser, $artist) {
            $superAdminId = $authenticatedUser['role'] === 'super_admin' 
                                ? $authenticatedUser['id']
                                : $authenticatedUser['super_admin_id'];
            return $superAdminId == $artist['super_admin_id'];
        });

        Gate::define('can-edit-artist', function ($authenticatedUser, $artist) {
            return $authenticatedUser['super_admin_id'] == $artist['super_admin_id'];
        });

        Gate::define('can-delete-artist', function ($authenticatedUser, $artist) {
            return $authenticatedUser['super_admin_id'] == $artist['super_admin_id'];
        });

    }
}
