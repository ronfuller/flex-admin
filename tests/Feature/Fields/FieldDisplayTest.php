<?php

use Psi\FlexAdmin\Fields\Enums\DisplayContext;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should create display defaults')
    ->expect(fn () => Field::make(null, 'id')->display)
    ->toBe([
        DisplayContext::INDEX->value => true,
        DisplayContext::DETAIL->value => true,
        DisplayContext::EDIT->value => true,
        DisplayContext::CREATE->value => true,
    ])
    ->group('display', 'fields');

it('should hide field from index')
    ->expect(fn () => Field::make(null, 'id')->hideFromIndex()->display)
    ->toHaveKey(DisplayContext::INDEX->value, false)
    ->toHaveKey(DisplayContext::DETAIL->value, true)
    ->group('display', 'fields');

it('should hide field from detail')
    ->expect(fn () => Field::make(null, 'id')->hideFromDetail()->display)
    ->toHaveKey(DisplayContext::DETAIL->value, false)
    ->toHaveKey(DisplayContext::INDEX->value, true)
    ->group('display', 'fields');

it('should hide field from create')
    ->expect(fn () => Field::make(null, 'id')->hideFromCreate()->display)
    ->toHaveKey(DisplayContext::CREATE->value, false)
    ->toHaveKey(DisplayContext::INDEX->value, true)
    ->group('display', 'fields');

it('should hide field from edit')
    ->expect(fn () => Field::make(null, 'id')->hideFromEdit()->display)
    ->toHaveKey(DisplayContext::EDIT->value, false)
    ->toHaveKey(DisplayContext::DETAIL->value, true)
    ->toHaveKey(DisplayContext::INDEX->value, true)
    ->group('display', 'fields');

it('should only have index context')
    ->expect(fn () => Field::make(null, 'id')->indexOnly()->display)
    ->toHaveKey(DisplayContext::INDEX->value, true)
    ->not->toHaveKeys([DisplayContext::DETAIL->value, DisplayContext::CREATE->value, DisplayContext::EDIT->value])
    ->group('display', 'fields');

it('should only have detail context')
    ->expect(fn () => Field::make(null, 'id')->detailOnly()->display)
    ->toHaveKey(DisplayContext::DETAIL->value, true)
    ->not->toHaveKeys([DisplayContext::INDEX->value, DisplayContext::CREATE->value, DisplayContext::EDIT->value])
    ->group('display', 'fields');

it('should only have edit context')
    ->expect(fn () => Field::make(null, 'id')->editOnly()->display)
    ->toHaveKey(DisplayContext::EDIT->value, true)
    ->not->toHaveKeys([DisplayContext::INDEX->value, DisplayContext::CREATE->value, DisplayContext::DETAIL->value])
    ->group('display', 'fields');

it('should only have create context')
    ->expect(fn () => Field::make(null, 'id')->createOnly()->display)
    ->toHaveKey(DisplayContext::CREATE->value, true)
    ->not->toHaveKeys([DisplayContext::INDEX->value, DisplayContext::EDIT->value, DisplayContext::DETAIL->value])
    ->group('display', 'fields');

it('should have a column hidden from index')
    ->expect(fn () => Field::make(null, 'id')->hideFromIndex()->model(new Property())->context(DisplayContext::INDEX->value)->toMeta())
    ->toHaveKey('enabled', false)
    ->group('display', 'fields');

it('should have a column hidden from detail')
    ->expect(fn () => Field::make(null, 'id')->indexOnly()->model(new Property())->context(DisplayContext::DETAIL->value)->toMeta())
    ->toHaveKey('enabled', false)
    ->group('display', 'fields');

it('should have enabled attributes false when hidden from index')
    ->expect(fn () => Field::make(null, 'created_at')
        ->hideFromIndex()
        ->model(new Property())
        ->context(DisplayContext::INDEX->value)
        ->toMeta())
    ->toHaveKey('enabled', false)
    ->group('display', 'fields');

it('should have enabled attribute false when index only')
    ->expect(fn () => Field::make(null, 'created_at')
        ->indexOnly()
        ->model(new Property())
        ->context(DisplayContext::DETAIL->value)
        ->toMeta())
    ->toHaveKey('enabled', false)
    ->group('display', 'fields');

it('should have enabled attribute false when detail only')
    ->expect(fn () => Field::make(null, 'created_at')
        ->detailOnly()
        ->model(new Property())
        ->context(DisplayContext::INDEX->value)
        ->toMeta())
    ->toHaveKey('enabled', false)
    ->group('display', 'fields');

it('should have enabled attribute false when create only')
    ->expect(fn () => Field::make(null, 'created_at')
        ->createOnly()
        ->model(new Property())
        ->context(DisplayContext::INDEX->value)
        ->toMeta())
    ->toHaveKeys(['component', 'panel', 'key', 'render'])
    ->toHaveKey('enabled', false)
    ->group('display', 'fields');
