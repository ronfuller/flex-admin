<?php

namespace Psi\FlexAdmin;

use Psi\FlexAdmin\Commands\FlexAdminCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FlexAdminServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('flex-admin')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_flex-admin_table')
            ->hasCommand(FlexAdminCommand::class);
    }
}
