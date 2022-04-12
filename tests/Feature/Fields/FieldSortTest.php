<?php


use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have a column that is the default sort')
    ->expect(fn () => Field::make(null, 'id')->defaultSort(direction: 'asc')->model(new Property())->toMeta())
    ->toHaveKey('defaultSort', true)
    ->toHaveKey('sortDir', 'asc')
    ->group('sort', 'fields');

it('should validate the sort direction', function () {
    expect(fn () => Field::make(null, 'id')->defaultSort(direction: 'invalid'))
        ->toThrow('Error in sort direction parameter');
})->group('sort', 'fields');
