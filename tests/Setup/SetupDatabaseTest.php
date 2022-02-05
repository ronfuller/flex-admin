<?php

it('can set up database migrations', function () {
    $this->setupMigrations();
    $this->assertDatabaseCount('properties', 0);
})->group('setup');
