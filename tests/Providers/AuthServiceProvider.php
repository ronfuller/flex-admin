<?php

namespace Psi\FlexAdmin\Tests\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Psi\FlexAdmin\Tests\Policies\PropertyPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('properties.view-any', [PropertyPolicy::class, 'viewAny']);
        Gate::define('properties.view', [PropertyPolicy::class, 'view']);
        Gate::define('properties.edit', [PropertyPolicy::class, 'update']);
        Gate::define('properties.update', [PropertyPolicy::class, 'update']);
        Gate::define('properties.store', [PropertyPolicy::class, 'create']);
        Gate::define('properties.create', [PropertyPolicy::class, 'create']);
        Gate::define('properties.destroy', [PropertyPolicy::class, 'delete']);
        Gate::define('properties.delete', [PropertyPolicy::class, 'delete']);
        Gate::define('properties.admin', [PropertyPolicy::class, 'admin']);
    }
}
