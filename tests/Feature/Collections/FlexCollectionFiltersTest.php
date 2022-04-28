<?php

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Route;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;
use Psi\FlexAdmin\Tests\Models\User;

beforeEach(function () {
    $this->status = '5JOYAE7QO8';

    $this->properties = Property::factory()->count(25)
        ->forCompany()
        ->state(new Sequence(
            ['created_at' => now()->subDays(5), 'name' => 'Everest', 'options' => ['color' => 'blue', ], 'status' => $this->status, 'type' => 'townhome'],
            ['created_at' => now()->subDays(3), 'name' => 'Cascade', 'options' => ['color' => 'green', ], 'status' => $this->status, 'type' => 'apartment'],
            ['created_at' => now()->subDays(10), 'name' => 'Denali', 'options' => ['color' => 'blue', ], 'status' => $this->status, 'type' => 'home'],
            ['created_at' => now()->subDays(13), 'name' => 'Cameroon', 'options' => ['color' => 'blue', ], 'status' => $this->status, 'type' => 'duplex'],
            ['created_at' => now()->subDays(35), 'name' => 'Rainier', 'options' => ['color' => 'red', ], 'status' => $this->status, 'type' => 'commercial'],
            ['created_at' => now(),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now(),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now()->subDays(45),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now()->subDays(55),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now()->subDays(70),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now()->subDays(85),  'status' => 'AJU2Z14UUQ'],
            ['created_at' => now()->subMonth()->firstOfMonth()->addDays(2), 'status' => '9850G5PW2O'],
            ['created_at' => now()->subMonth()->firstOfMonth()->addDays(4), 'status' => '9850G5PW2O'],
            ['created_at' => now()->firstOfQuarter()->addHours(2), 'status' => 'JN6ZSAJLHM'],
            ['created_at' => now()->firstOfQuarter()->addHours(4), 'status' => 'JN6ZSAJLHM'],
            ['created_at' => now()->subHours(1),  'status' => '0NYYUYW9DF', ],
            ['created_at' => now()->subHours(2),  'status' => '0NYYUYW9DF', ],
            ['created_at' => now()->subQuarter()->firstOfQuarter()->addDays(2),  'status' => 'O4IGPQ4FGW', ],
            ['created_at' => now()->subQuarter()->firstOfQuarter()->addDays(4),  'status' => 'O4IGPQ4FGW', ],
            ['created_at' => now()->firstOfYear()->addDays(1),  'status' => '5R2O5O63MQ', ],
            ['created_at' => now()->firstOfYear()->addDays(2),  'status' => '5R2O5O63MQ', ],
            ['created_at' => now()->subYear()->firstOfYear()->addDays(2),  'status' => '5R2O5O63MQ', ],
            ['created_at' => now()->subYear()->firstOfYear()->addDays(1),  'status' => '4FI6DUVKNC', ],
            ['created_at' => now()->subYear()->firstOfYear()->addDays(2),  'status' => '4FI6DUVKNC', ],
            ['created_at' => now()->subYears(2)->firstOfYear()->addDays(2),  'status' => '4FI6DUVKNC', ],
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
    ->expect(fn () => Flex::forIndex(Property::class)->withoutCache()->toArray(createRequest()))
    ->filters
    ->toHaveCount(4)
    ->group('collections', 'filter');

it('should constrain on status')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['status' => '5JOYAE7QO8']))
        ->count())
    ->toBe(5)
    ->group('collections', 'constraint');

it('should filter the query by multiple filters')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->query(createRequest(['filter' => 'type:apartment;color:green', 'status' => '5JOYAE7QO8']))->resource)
    ->toHaveCount(1)
    ->group('collections', 'filter');

it('should filter the query by a date range of last 7 days')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutDefaultFilters()
        ->query(createRequest(['filter' => 'created_at:Last 7 days', 'status' => '5JOYAE7QO8']))->resource)
    ->toHaveCount(2)
    ->group('collections', 'filter');

it('should filter the query by last 4 hours')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutDefaultFilters()
        ->query(createRequest(['filter' => 'created_at:Last 4 hours (new)', 'status' => '0NYYUYW9DF']))->resource)
    ->toHaveCount(2)
    ->group('collections', 'filter');

it('should filter the query by last 14 days')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutDefaultFilters()
        ->query(createRequest(['filter' => 'created_at:Last 14 days', 'status' => '5JOYAE7QO8']))->resource)
    ->toHaveCount(4)
    ->group('collections', 'filter');

it('should filter the query by last 30 days')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutDefaultFilters()
        ->query(createRequest(['filter' => 'created_at:Last 30 days', 'status' => '5JOYAE7QO8']))->resource)
    ->toHaveCount(4)
    ->group('collections', 'filter');

it('should filter the query by last 60 days')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutDefaultFilters()
        ->query(createRequest(['filter' => 'created_at:Last 60 days', 'status' => 'AJU2Z14UUQ']))->resource)
    ->toHaveCount(4)
    ->group('collections', 'filter');

it('should filter the query by last 90 days')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:Last 90 days', 'status' => 'AJU2Z14UUQ']))->resource)
    ->toHaveCount(6)
    ->group('collections', 'filter');

it('should filter the query by this month')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:This Month', 'status' => 'AJU2Z14UUQ']))->resource)
    ->toHaveCount(2)
    ->group('collections', 'filter');

it('should filter the query by last month')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:Last Month', 'status' => '9850G5PW2O']))->resource)
    ->toHaveCount(2)
    ->group('collections', 'filter');

it('should filter the query by this quarter')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:This Quarter', 'status' => 'JN6ZSAJLHM']))->resource)
    ->toHaveCount(2)
    ->group('collections', 'filter');

it('should filter the query by last quarter')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:Last Quarter', 'status' => 'O4IGPQ4FGW']))->resource)
    ->toHaveCount(2)
    ->group('collections', 'filter');

it('should filter the query by this year')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:This Year', 'status' => '5R2O5O63MQ']))->resource)
    ->toHaveCount(2)
    ->group('collections', 'filter');

it('should filter the query by last year')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:Last Year', 'status' => '4FI6DUVKNC']))->resource)
    ->toHaveCount(2)
    ->group('collections', 'filter');

it('should throw error on invalid date range filter', function () {
    expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutDefaultFilters()->query(createRequest(['filter' => 'created_at:invalid', 'status' => 'O4IGPQ4FGW']))->resource)
        ->toThrow('Error in date range filter');
})->group('collections', 'filter');

it('should return filter options for types')
    ->expect(fn () => Flex::forIndex(Property::class)->withoutDeferredFilters()->toArray(createRequest(['status' => '5JOYAE7QO8'])))
    ->filters
    ->toHaveKey('1.options.0.label', 'Apartment')
    ->toHaveKey('1.options.4.label', 'Townhome')
    ->group('filter');

it('should filter the resource query')
    ->expect(fn () => Flex::forIndex(Property::class)->query(createRequest(['filter' => 'type:apartment;color:green', 'status' => '5JOYAE7QO8']))->resource)
    ->toHaveCount(1)
    ->group('collections', 'filter');

it('should create filter options for query')
    ->expect(fn () => Flex::forIndex(Property::class)
        ->withoutDefaultFilters()
        ->queryFilters(createRequest()))
    ->flexFilters
    ->each(fn ($filter) => $filter->options->not->toBeEmpty())
    ->group('collections', 'filter');

it('should create filter options for query when not deferred')
    ->expect(fn () => Flex::forIndex(Property::class)
        ->withoutDefaultFilters()
        ->withoutDeferredFilters()
        ->toArray(createRequest()))
    ->filters
    ->each(fn ($filter) => $filter->options->not->toBeEmpty())
    ->group('collections', 'filter');

it('should apply filter from a query scope')
    ->expect(
        fn () => Flex::forIndex(Property::class)
            ->withoutDefaultFilters()
            ->query(createRequest(['filter' => "company:{$this->properties->first()->company->id}"]))
            ->resource
            ->total()
    )
    ->toBe(25)
    ->group('collections', 'filter');
