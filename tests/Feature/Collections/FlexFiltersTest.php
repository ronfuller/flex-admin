<?php


use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Route;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;
use Psi\FlexAdmin\Tests\Models\User;

beforeEach(function () {
    $this->status = '5JOYAE7QO8';

    $this->properties = Property::factory()->count(11)
        ->forCompany()
        ->state(new Sequence(
            ['created_at' => now()->subDays(5), 'name' => 'Everest', 'options' => ['color' => 'blue',], 'status' => $this->status, 'type' => 'townhome'],
            ['created_at' => now()->subDays(3), 'name' => 'Cascade', 'options' => ['color' => 'green',], 'status' => $this->status, 'type' => 'apartment'],
            ['created_at' => now()->subDays(10), 'name' => 'Denali', 'options' => ['color' => 'blue',], 'status' => $this->status, 'type' => 'home'],
            ['created_at' => now()->subDays(13), 'name' => 'Cameroon', 'options' => ['color' => 'blue',], 'status' => $this->status, 'type' => 'duplex'],
            ['created_at' => now()->subDays(35), 'name' => 'Rainier', 'options' => ['color' => 'red',], 'status' => $this->status, 'type' => 'commercial'],
            ['created_at' => now(),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now(),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now()->subDays(45),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now()->subDays(55),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now()->subDays(70),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now()->subDays(85),  'status' => 'AJU2Z14UUQ'],
        ))
        ->create();
    $this->user = User::factory()->create(
        [
            'permissions' => ['properties.view-any', 'properties.view', 'properties.edit', 'properties.delete', 'properties.create'],
        ]
    );
    actingAs($this->user);
    Route::resource('properties', TestController::class);
});

it('should return filters')
    ->expect(fn () => Flex::forIndex(Property::class)->toArray(createRequest()))
    ->filters
    ->toHaveCount(4)
    ->group('filter');

it('should constrain on status')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['status' => '5JOYAE7QO8']))
        ->count())
    ->toBe(5)
    ->group('constraint');


it('should filter the query by multiple filters')
    ->expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)->query(createRequest(['filter' => 'type:apartment|color:green', 'status' => '5JOYAE7QO8']))->resource)
    ->toHaveCount(1)
    ->group('filter');

it('should filter the query by a date range')
    ->expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:Last 7 days', 'status' => '5JOYAE7QO8']))->resource)
    ->toHaveCount(2)
    ->group('filter');

it('should filter the query by last 30 days')
    ->expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:Last 30 days', 'status' => '5JOYAE7QO8']))->resource)
    ->toHaveCount(4)
    ->group('filter');

it('should filter the query by last 60 days')
    ->expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:Last 60 days', 'status' => 'AJU2Z14UUQ']))->resource)
    ->toHaveCount(4)
    ->group('filter');

it('should filter the query by last 90 days')
    ->expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:Last 90 days', 'status' => 'AJU2Z14UUQ']))->resource)
    ->toHaveCount(6)
    ->group('filter');

it('should filter the query by this month')
    ->expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:This Month', 'status' => 'AJU2Z14UUQ']))->resource)
    ->toHaveCount(2)
    ->group('filter');

it('should return filter options for types')
    ->expect(fn () => Flex::forIndex(Property::class)->withoutDeferredFilters()->toArray(createRequest(['status' => '5JOYAE7QO8'])))
    ->filters
    ->toHaveKey('1.options.0.label', 'Apartment')
    ->toHaveKey('1.options.4.label', 'Townhome')
    ->group("filter");

it('should filter the resource query')
    ->expect(fn () => Flex::forIndex(Property::class)->query(createRequest(['filter' => 'type:apartment|color:green', 'status' => '5JOYAE7QO8']))->resource)
    ->toHaveCount(1)
    ->group('filter');
