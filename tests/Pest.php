<?php

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\assertTrue;
use Psi\FlexAdmin\Tests\Http\Controllers\PropertyController;
use Psi\FlexAdmin\Tests\Models\ApplicationGroup;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;
use Psi\FlexAdmin\Tests\Models\User;
use Psi\FlexAdmin\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);
uses(DatabaseTransactions::class)->in('Feature');

expect()->extend('toBeArrayList', function () {
    assertTrue(array_is_list($this->value), 'Failed asserting that value is an array list');

    return $this;
});

/**
 * Set the currently logged in user for the application.
 *
 * @return TestCase
 */
function actingAs(User $user, string $driver = null)
{
    return test()->actingAs($user, $driver);
}

function createRequest(array $params = []): Request
{
    return  Request::create('http://test.com', 'GET', $params);
}

function buildFilter(string $filter): string
{
    return (string) str($filter)->replace(';', config('flex-admin.filter.delimiter'));
}

uses()
    ->beforeEach(function () {
        $this->property = Property::factory()->create(
            [
                'created_at' => '2020-01-19 12:00:01',
                'name' => 'Test Property',
            ]
        );
        $this->user = User::first();
        actingAs($this->user);
    })
    ->in('Feature/Fields');

uses()
    ->beforeEach(function () {
        $this->property = Property::factory()->forCompany()->create(
            [
                'name' => 'Test Property',
            ]
        );
        $this->company = Company::factory()->create();
        $this->applicationGroup = ApplicationGroup::factory()->make();
        $this->user = User::first();
        actingAs($this->user);
        $this->user->givePermissionTo('units.view-any');
        Route::resource('properties', TestController::class);
        Route::resource('companies', TestController::class);
        Route::resource('units', TestController::class);
    })
    ->in('Feature/Resources');

uses()
    ->beforeEach(function () {
        $this->properties = Property::factory()->count(5)
            ->for(
                Company::factory()->state(['name' => 'Columbia'])
            )
            ->state(new Sequence(
                ['created_at' => now()->subDays(5), 'name' => 'Everest', 'options' => ['color' => 'blue'], 'status' => 'success', 'type' => 'townhome'],
                ['created_at' => now()->subDays(3), 'name' => 'Cascade', 'options' => ['color' => 'green'], 'status' => 'fail', 'type' => 'apartment'],
                ['created_at' => now()->subDays(10), 'name' => 'Denali', 'options' => ['color' => 'violet'], 'status' => 'fail', 'type' => 'home'],
                ['created_at' => now()->subDays(13), 'name' => 'Cameroon', 'options' => ['color' => 'blue green'], 'status' => 'fail', 'type' => 'duplex'],
                ['created_at' => now()->subDays(35), 'name' => 'Rainier', 'options' => ['color' => 'light blue'], 'status' => 'fail', 'type' => 'commercial'],
            ))
            ->create();
        //$this->user = User::first();

        $this->user = User::factory()->create(
            [
                'permissions' => ['properties.view-any', 'properties.view', 'properties.edit', 'properties.delete', 'properties.create', 'companies.view', 'companies.edit', 'units.view-any', 'units.view'],
            ]
        );
        actingAs($this->user);
        Route::resource('properties', PropertyController::class)->middleware(['web']);
    })
    ->in('Feature/Collections');
