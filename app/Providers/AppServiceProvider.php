<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
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
        Blade::if('role', function ($roles) {

            if (!Auth::check()) {
                return false;
            }

            $userRole = Auth::user()->role->value;

            // If array → check if user role is inside it
            if (is_array($roles)) {
                return in_array($userRole, $roles);
            }

            // If single role
            return $userRole === $roles;
        });
    }
}
