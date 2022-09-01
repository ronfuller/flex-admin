<?php

use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have an ordered query name desc')
    ->expect(fn () => Flex::forIndex(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->toArray(createRequest())['rows'][0])
    ->name->value
    ->toBe('Rainier')

    ->group('collections', 'order');

it('should have an ordered query type asc')
    ->expect(fn () => Flex::forIndex(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->toArray(createRequest(['sort' => 'type', 'descending' => false]))['rows'][0])
    ->type->value

    ->toBe('apartment')
    ->group('collections', 'order');

it('should throw an error when there is no default sort order', function () {
    expect(fn () => Flex::forIndex(Company::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->toArray(createRequest()))
        ->toThrow('Error. Default sort is required for resource.');
})->group('collections', 'order');

it('should throw an error when there is an invalid sort direction', function () {
    config(['flex-admin.sort.direction.flag' => null]);
    expect(fn () => Flex::forIndex(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->toArray(createRequest(['sort' => 'type', 'descending' => 'abc'])))
        ->toThrow('Invalid sort direction');
})->group('collections', 'order');

it('should have an ordered query type desc', function () {
    $request = createRequest(['sort' => 'type', 'descending' => 'true']);

    $rows = Flex::forIndex(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->toArray($request)['rows'];
    expect($rows[0])->type->value->toBe('townhome');
})
    ->group('collections', 'order');
