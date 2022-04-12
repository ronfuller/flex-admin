<?php

use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have an ordered query name desc')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest())
        ->resource->first())
    ->name
    ->toBe('Rainier')
    ->group('collections', 'order');

it('should have an ordered query type asc')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest(['sort' => 'type', 'descending' => false]))
        ->resource->first())
    ->type
    ->toBe('apartment')
    ->group('collections', 'order');

it('should throw an error when there is no default sort order', function () {
    expect(fn () => Flex::for(Company::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest()))
        ->toThrow("Error. Default sort is required for resource.");
})->group('collections', 'order');

it('should throw an error when there is an invalid sort direction', function () {
    config(['flex-admin.sort.direction.flag' => null]);
    expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest(['sort' => 'type', 'descending' => 'abc'])))
        ->toThrow("Invalid sort direction");
})->group('collections', 'order');


it('should have an ordered query type desc')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest(['sort' => 'type', 'descending' => "true"]))
        ->resource->first())
    ->type
    ->toBe('townhome')
    ->group('collections', 'order');
