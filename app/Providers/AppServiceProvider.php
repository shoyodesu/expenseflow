<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate; // Add this import at the top
use App\Models\User;
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
        // Define who is allowed to manage users
        Gate::define('admin-only', function (User $user) {
            return $user->role === 'admin';
        });
    }
}
