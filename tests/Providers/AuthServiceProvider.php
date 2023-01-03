<?php

namespace Psi\FlexAdmin\Tests\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Psi\FlexAdmin\Tests\Policies\CompanyPolicy;
use Psi\FlexAdmin\Tests\Policies\PropertyPolicy;
use Psi\FlexAdmin\Tests\Policies\UnitPolicy;

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

        Gate::define('companies.view-any', [CompanyPolicy::class, 'viewAny']);
        Gate::define('companies.view', [CompanyPolicy::class, 'view']);
        Gate::define('companies.edit', [CompanyPolicy::class, 'update']);
        Gate::define('companies.update', [CompanyPolicy::class, 'update']);
        Gate::define('companies.store', [CompanyPolicy::class, 'create']);
        Gate::define('companies.create', [CompanyPolicy::class, 'create']);
        Gate::define('companies.destroy', [CompanyPolicy::class, 'delete']);
        Gate::define('companies.delete', [CompanyPolicy::class, 'delete']);
        Gate::define('companies.admin', [CompanyPolicy::class, 'admin']);

        Gate::define('units.view-any', [UnitPolicy::class, 'viewAny']);
        Gate::define('units.view', [UnitPolicy::class, 'view']);
        Gate::define('units.edit', [UnitPolicy::class, 'update']);
        Gate::define('units.update', [UnitPolicy::class, 'update']);
        Gate::define('units.store', [UnitPolicy::class, 'create']);
        Gate::define('units.create', [UnitPolicy::class, 'create']);
        Gate::define('units.destroy', [UnitPolicy::class, 'delete']);
        Gate::define('units.delete', [UnitPolicy::class, 'delete']);
        Gate::define('units.admin', [UnitPolicy::class, 'admin']);
    }
}
