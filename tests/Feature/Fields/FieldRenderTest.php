<?php

use Psi\FlexAdmin\Fields\Enums\DisplayContext;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have default render properties')
    ->expect(fn () => Field::make(null, 'id')->model(new Property())->toMeta())
    ->toHaveKey('panel', 'details')
    ->group('render', 'fields');

it('should have a detail context specific component')
    ->expect(fn () => Field::make(null, 'name')->detailComponent('chip-field')->model(new Property())->context(DisplayContext::DETAIL->value)->toMeta())
    ->component
    ->toBe('chip-field')
    ->group('render', 'fields');

it('should have an edit context specific component')
    ->expect(fn () => Field::make(null, 'name')->editComponent('chip-field')->model(new Property())->context(DisplayContext::DETAIL->value)->toMeta())
    ->component
    ->toBe('text-field')
    ->group('render', 'fields');

it('should have a create context specific component')
    ->expect(fn () => Field::make(null, 'name')->createComponent('chip-field')->model(new Property())->context(DisplayContext::CREATE->value)->toMeta())
    ->component
    ->toBe('chip-field')
    ->group('render', 'fields');

it('should have an index context specific component')
    ->expect(fn () => Field::make(null, 'name')->indexComponent('chip-field')->model(new Property())->context(DisplayContext::INDEX->value)->toMeta())
    ->component
    ->toBe('chip-field')
    ->group('render', 'fields');
