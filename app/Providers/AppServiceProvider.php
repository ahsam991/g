<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\Destination;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\University;
use App\Models\User;
use App\Policies\CmsPolicy;
use App\Services\Settings;
use App\Services\Uid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Settings::class);
        $this->app->singleton(Uid::class);
    }

    public function boot(): void
    {
        $cmsPolicy = CmsPolicy::class;
        Gate::policy(Destination::class, $cmsPolicy);
        Gate::policy(University::class, $cmsPolicy);
        Gate::policy(Course::class, $cmsPolicy);
        Gate::policy(Service::class, $cmsPolicy);
        Gate::policy(Partner::class, $cmsPolicy);
        Gate::policy(Testimonial::class, $cmsPolicy);

        // Admin passes every gate and policy check.
        Gate::before(fn (User $user) => $user->isAdmin() ? true : null);

        // Every permission slug is also usable as a gate: @can('documents.verify').
        Gate::after(fn (User $user, string $ability) => $user->hasPermission($ability) ?: null);

        // Surface N+1 queries and mass-assignment mistakes during development.
        Model::preventLazyLoading(! app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(! app()->isProduction());
    }
}
