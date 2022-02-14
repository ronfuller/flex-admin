<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should make a resource field')
    ->expect(Field::make(null, 'id'))
    ->not()
    ->toBeNull()
    ->group('fields');

it('should be null if resource field is not in column list')
    ->expect(fn () => Field::make(['name'], 'id'))
    ->toBeNull()
    ->group('fields');

it('should have a column')
    ->expect(fn () => Field::make(null, 'id')->model(new Property())->context(Field::CONTEXT_INDEX)->toColumn())
    ->toHaveKeys(['component', 'render', 'label', 'enabled', 'key', 'name', 'sortable', 'searchable', 'constrainable', 'select', 'align', 'sort', 'defaultSort', 'sortDir'])
    ->group('fields');
