<?php

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Route;
use Psi\FlexAdmin\Tests\Feature\Collections\FlexFilterWrapper;
use Psi\FlexAdmin\Tests\Models\Property;
use Psi\FlexAdmin\Tests\Models\User;

beforeEach(function () {
    $this->properties = Property::factory()->count(5)
        ->state(new Sequence(
            ['name' => 'Everest', 'options' => ['color' => 'blue'], 'type' => 'townhome'],
            ['name' => 'Cascade', 'options' => ['color' => 'green'], 'type' => 'apartment'],
            ['name' => 'Denali', 'options' => ['color' => 'violet'], 'type' => 'home'],
            ['name' => 'Cameroon', 'options' => ['color' => 'blue green'], 'type' => 'duplex'],
            ['name' => 'Rainier', 'options' => ['color' => 'light blue'], 'type' => 'commercial'],
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

it('should parse filter values')
    ->expect(fn () => (new FlexFilterWrapper())->wrapParseFilter(['filter' => 'company:1;property:2']))
    ->toBe(['company' => 1, 'property' => 2])
    ->group('filter');

it('should parse filter values with floats, spaces, dashes')
    ->expect(fn () => (new FlexFilterWrapper())->wrapParseFilter(['filter' => 'company:1.0;property:2;type:  this ; some_thing: else-or-else']))
    ->toBe(['company' => 1.0, 'property' => 2, 'type' => 'this', 'some_thing' => 'else-or-else'])
    ->group('filter');

it('should create attributes from filters')
    ->expect(fn () => (new FlexFilterWrapper())->wrapFiltersAsAttributes(
        [[
            'name' => 'types',
            'value' => ['label' => 'Small', 'value' => 'small'],
            'optionValue' => 'value',
            'optionLabel' => 'label',
        ]]
    ))
    ->toBe([
        'filter' => 'types:small',
    ])
    ->group('filter');

it('should create attributes from multiple filters')
    ->expect(fn () => (new FlexFilterWrapper())->wrapFiltersAsAttributes(
        [
            [
                'name' => 'types',
                'value' => ['label' => 'Small', 'value' => 'small'],
                'optionValue' => 'value',
                'optionLabel' => 'label',
            ],
            [
                'name' => 'colors',
                'value' => ['label' => 'Blue', 'value' => 'blue'],
                'optionValue' => 'value',
                'optionLabel' => 'label',
            ],
        ]
    ))
    ->toBe([
        'filter' => 'types:small|colors:blue',
    ])
    ->group('filter');

it('should get filters from attributes')
    ->expect(fn () => (new FlexFilterWrapper())->wrapGetFilters(['filter' => 'company%3A123;type:small']))
    ->toHaveKey('0.value', 123)
    ->toHaveKey('1.value', 'small')
    ->toHaveKey('1.item.label', 'Small')
    ->toHaveKey('1.item.value', 'small')
    ->group('filter')
    ->only();
