<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have a qualified select')
    ->expect(fn () => Field::make(null, 'id')->model(new Property())->toColumn())
    ->toHaveKey('select', 'properties.id')
    ->group('select', 'fields');

it('should have a qualified select for a json column')
    ->expect(fn () => Field::make(null, 'color')->select('option->color')->model(new Property())->toColumn())
    ->toHaveKey('select', 'properties.option->color as color')
    ->group('select', 'fields');

it('should have a column with null select when key is not in table')
    ->expect(fn () => Field::make(null, 'no_column')->model(new Property())->toColumn())
    ->toHaveKey('select', null)
    ->group('select', 'fields');
