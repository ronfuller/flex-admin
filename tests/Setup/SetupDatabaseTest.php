<?php

use Psi\FlexAdmin\Tests\Models\User;

it('can set up database migrations', function () {
    $this->setupMigrations();
    $this->assertDatabaseCount('properties', 0);
    $this->user = User::factory()->create(
        [
            'permissions' => ['properties.view-any', 'properties.view', 'properties.edit', 'properties.create', 'properties.delete'],
        ]
    );
})->group('setup');
