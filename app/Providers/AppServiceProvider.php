<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Admin passes every gate and policy check.
        Gate::before(fn (User $user) => $user->isAdmin() ? true : null);

        // Every permission slug is also usable as a gate: @can('documents.verify').
        Gate::after(fn (User $user, string $ability) => $user->hasPermission($ability) ?: null);

        // Surface N+1 queries and mass-assignment mistakes during development.
        Model::preventLazyLoading(! app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(! app()->isProduction());
    }
}
