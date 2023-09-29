<?php

use Psi\FlexAdmin\Tests\Models\User;

it('can set up database migrations', function () {
    $this->setupMigrations();
    $this->assertDatabaseCount('properties', 0);
    $this->user = User::factory()->state(['name' => 'Frederick Flintstone'])->create(
        [
            'permissions' => ['properties.view-any', 'properties.view', 'properties.edit', 'properties.delete', 'properties.create', 'companies.view', 'companies.edit', 'units.view-any', 'units.view'],

            // 'permissions' => ['properties.view-any', 'properties.view', 'properties.edit', 'properties.create', 'properties.delete'],
        ]
    );
})->group('setup');
