<?php


use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should create display defaults')
    ->expect(fn () => Field::make(null, 'id')->display)
    ->toBe([
        Field::CONTEXT_INDEX => true,
        Field::CONTEXT_DETAIL => true,
        Field::CONTEXT_EDIT => true,
        Field::CONTEXT_CREATE => true,
    ])
    ->group('display', 'fields');

it('should hide field from index')
    ->expect(fn () => Field::make(null, 'id')->hideFromIndex()->display)
    ->toHaveKey(Field::CONTEXT_INDEX, false)
    ->toHaveKey(Field::CONTEXT_DETAIL, true)
    ->group('display', 'fields');

it('should hide field from detail')
    ->expect(fn () => Field::make(null, 'id')->hideFromDetail()->display)
    ->toHaveKey(Field::CONTEXT_DETAIL, false)
    ->toHaveKey(Field::CONTEXT_INDEX, true)
    ->group('display', 'fields');

it('should hide field from create')
    ->expect(fn () => Field::make(null, 'id')->hideFromCreate()->display)
    ->toHaveKey(Field::CONTEXT_CREATE, false)
    ->toHaveKey(Field::CONTEXT_INDEX, true)
    ->group('display', 'fields');

it('should hide field from edit')
    ->expect(fn () => Field::make(null, 'id')->hideFromEdit()->display)
    ->toHaveKey(Field::CONTEXT_EDIT, false)
    ->toHaveKey(Field::CONTEXT_DETAIL, true)
    ->toHaveKey(Field::CONTEXT_INDEX, true)
    ->group('display', 'fields');

it('should only have index context')
    ->expect(fn () => Field::make(null, 'id')->indexOnly()->display)
    ->toHaveKey(Field::CONTEXT_INDEX, true)
    ->not->toHaveKeys([Field::CONTEXT_DETAIL, Field::CONTEXT_CREATE, Field::CONTEXT_EDIT])
    ->group('display', 'fields');

it('should only have detail context')
    ->expect(fn () => Field::make(null, 'id')->detailOnly()->display)
    ->toHaveKey(Field::CONTEXT_DETAIL, true)
    ->not->toHaveKeys([Field::CONTEXT_INDEX, Field::CONTEXT_CREATE, Field::CONTEXT_EDIT])
    ->group('display', 'fields');

it('should only have edit context')
    ->expect(fn () => Field::make(null, 'id')->editOnly()->display)
    ->toHaveKey(Field::CONTEXT_EDIT, true)
    ->not->toHaveKeys([Field::CONTEXT_INDEX, Field::CONTEXT_CREATE, Field::CONTEXT_DETAIL])
    ->group('display', 'fields');

it('should only have create context')
    ->expect(fn () => Field::make(null, 'id')->createOnly()->display)
    ->toHaveKey(Field::CONTEXT_CREATE, true)
    ->not->toHaveKeys([Field::CONTEXT_INDEX, Field::CONTEXT_EDIT, Field::CONTEXT_DETAIL])
    ->group('display', 'fields');

it('should have a column hidden from index')
    ->expect(fn () => Field::make(null, 'id')->hideFromIndex()->model(new Property())->context(Field::CONTEXT_INDEX)->toColumn())
    ->toHaveKey('enabled', false)
    ->group('display', 'fields');

it('should have a column hidden from detail')
    ->expect(fn () => Field::make(null, 'id')->indexOnly()->model(new Property())->context(Field::CONTEXT_DETAIL)->toColumn())
    ->toHaveKey('enabled', false)
    ->group('display', 'fields');

it('should have enabled attributes false when hidden from index')
    ->expect(fn () => Field::make(null, 'created_at')
        ->hideFromIndex()
        ->context(Field::CONTEXT_INDEX)
        ->toAttributes())
    ->toHaveKey('attributes.enabled', false)
    ->group('display', 'fields');

it('should have enabled attribute false when index only')
    ->expect(fn () => Field::make(null, 'created_at')
        ->indexOnly()
        ->context(Field::CONTEXT_DETAIL)
        ->toAttributes())
    ->toHaveKey('attributes.enabled', false)
    ->group('display', 'fields');

it('should have enabled attribute false when detail only')
    ->expect(fn () => Field::make(null, 'created_at')
        ->detailOnly()
        ->context(Field::CONTEXT_INDEX)
        ->toAttributes())
    ->toHaveKey('attributes.enabled', false)
    ->group('display', 'fields');

it('should have enabled attribute false when create only')
    ->expect(fn () => Field::make(null, 'created_at')
        ->createOnly()
        ->context(Field::CONTEXT_INDEX)
        ->toAttributes())
    ->toHaveKeys(['component', 'panel', 'attributes', 'key', 'render'])
    ->toHaveKey('attributes.enabled', false)
    ->group('display', 'fields');
