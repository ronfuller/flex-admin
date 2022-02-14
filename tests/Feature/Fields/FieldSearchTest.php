
<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should be searchable')
    ->expect(fn () => Field::make(null, 'id')->searchable()->attributes)
    ->toHaveKey('searchable', true)
    ->group('search', 'fields');


it('should have a searchable type')
    ->expect(fn () => Field::make(null, 'name')
        ->searchable('full')
        ->model(new Property())
        ->toColumn())
    ->toHaveKey('searchType', 'full')
    ->group('search', 'fields');;

it('should have a default searchable type')
    ->expect(fn () => Field::make(null, 'name')
        ->model(new Property())
        ->toColumn())
    ->toHaveKey('searchType', 'full')
    ->group('search', 'fields');;

it('should throw error on invalid searchable type', function () {
    expect(fn () => Field::make(null, 'name')
        ->searchable('invalid'))
        ->toThrow("Invalid search type");
})
    ->group('search', 'fields');;
