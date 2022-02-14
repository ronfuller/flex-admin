<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have default render properties')
    ->expect(fn () => Field::make(null, 'id')->toAttributes(Field::CONTEXT_INDEX))
    ->toHaveKey('panel', 'details')
    ->group('render', 'fields');

it('should have a detail context specific component')
    ->expect(fn () => Field::make(null, 'name')->detailComponent('chip-field')->model(new Property())->context(Field::CONTEXT_DETAIL)->toColumn())
    ->component
    ->toBe('chip-field')
    ->group('render', 'fields');

it('should have an edit context specific component')
    ->expect(fn () => Field::make(null, 'name')->editComponent('chip-field')->model(new Property())->context(Field::CONTEXT_DETAIL)->toColumn())
    ->component
    ->toBe('text-field')
    ->group('render', 'fields');

it('should have a create context specific component')
    ->expect(fn () => Field::make(null, 'name')->createComponent('chip-field')->model(new Property())->context(Field::CONTEXT_CREATE)->toColumn())
    ->component
    ->toBe('chip-field')
    ->group('render', 'fields');

it('should have an index context specific component')
    ->expect(fn () => Field::make(null, 'name')->indexComponent('chip-field')->model(new Property())->context(Field::CONTEXT_INDEX)->toColumn())
    ->component
    ->toBe('chip-field')
    ->group('render', 'fields');
