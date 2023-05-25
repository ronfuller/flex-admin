<?php

namespace Psi\FlexAdmin\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Psi\FlexAdmin\FlexAdminServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Psi\\FlexAdmin\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FlexAdminServiceProvider::class,
            \Spatie\LaravelRay\RayServiceProvider::class,
            \Spatie\LaravelData\LaravelDataServiceProvider::class,
            \Psi\FlexAdmin\Tests\Providers\AuthServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $data = include __DIR__.'/config/data.php';
        foreach ($data as $key => $value) {
            $app['config']->set("data.{$key}", $value);
        }

        $app['config']->set('app.key', 'base64:8xem+lYvuVAYkd/UvLjmG4cptCp4aOuWCz7Zn7dXcVo=');
        $app['config']->set('flex-admin.resource_path', 'Http\\Resources');
        $app['config']->set('flex-admin.filter.session_cache', false);
        $app['config']->set('flex-admin.model_path', 'Models');
        $app['config']->set('flex-admin.render.default_component', 'text-field');
        $app['config']->set('flex-admin.render.default_panel', 'details');
        $app['config']->set('flex-admin.search.attribute', 'search');
        $app['config']->set('flex-admin.sort.attribute', 'sort');
        $app['config']->set('flex-admin.sort.direction.attribute', 'descending');
        $app['config']->set('flex-admin.sort.direction.flag', 'desc');
        $app['config']->set('flex-admin.pagination.per_page_options', [5, 15, 25, 50, 75, 100]);
    }

    protected function setupMigrations()
    {
        Schema::dropAllTables();
        collect([
            // Package Migrations
            // '/../database/migrations/create_flex_admins_table.php.stub',

            // Test Migrations
            '/database/migrations/2014_10_12_000000_testbench_create_users_table.php',
            '/database/migrations/2022_01_03_000000_testbench_create_companies_table.php',
            '/database/migrations/2022_01_12_000000_testbench_create_properties_table.php',
            '/database/migrations/2022_04_13_101320_testbench_create_units_table.php',

        ])->each(function ($path) {
            $migration = include __DIR__.$path;
            $migration->up();
        });
    }
}
