<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Filters\Filter;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have filters as array items')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->toMeta(new Property()))
    ->filters
    ->toHaveCount(4)
    ->each
    ->toBeArray()
    ->group('resources', 'filter');

it('should have filters as filter items')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->toFilters(asArrayItems: false, model: new Property()))
    ->toHaveCount(4)
    ->each
    ->toBeInstanceOf(Filter::class)
    ->group('resources', 'filter');

it('should have filter items for default values')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->toMeta(new Property()))
    ->filters
    ->toHaveKey("1.item", ['label' => 'Small', 'value' => 'small'])
    ->toHaveKey("2.item", ['label' => 'Blue', 'value' => 'blue'])
    ->group('resources', 'filter');

it('should have empty filters when without filters is set')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->withoutFilters()->toMeta(new Property()))
    ->filters
    ->toBe([])
    ->group('resources', 'filter');

it('should create filters with meta information')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->toMeta(new Property())['filters'])
    ->each(fn ($filter) => $filter->toHaveKey('meta.column'))
    ->group('resources', 'filter');

it('should throw error when creating a filter on a non-filterable key', function () {
    $filter = Filter::make('name')->fromAttribute();
    expect(fn () => (new PropertyResource($this->property))->addFilter($filter)->withContext(Field::CONTEXT_INDEX)->toMeta(new Property()))
        ->toThrow("Filter for key = name is not filterable");
})
    ->group('resources', 'filter');

it('should get a filter by name')
    ->expect(fn () => (new PropertyResource($this->property))->getFilter('type'))
    ->toBeInstanceOf(Filter::class)
    ->group('resources', 'filter');

it('should return null for filter by missing name')
    ->expect(fn () => (new PropertyResource($this->property))->getFilter('invalid'))
    ->toBeNull()
    ->group('resources', 'filter');
