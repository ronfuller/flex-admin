<?php

use Psi\FlexAdmin\Fields\Field;

it('should set a default camel case name')
    ->expect(fn () => Field::make(null, 'property_title')->attributes)
    ->toHaveKey('name', 'propertyTitle')
    ->group('attributes', 'fields');

it('should set a default title case label')
    ->expect(fn () => Field::make(null, 'property_title')->attributes)
    ->toHaveKey('label', 'Property Title')
    ->group('attributes', 'fields');

it('should merge custom attributes')
    ->expect(fn () => Field::make(null, 'property_title')->attributes([
        'align' => 'left',
        'url' => 'https://www.google.com',
    ])->attributes)
    ->toHaveKeys(['name', 'label', 'align', 'url'])
    ->group('attributes', 'fields');

it('should overwrite the label attribute')
    ->expect(fn () => Field::make(null, 'property_title')->attributes([
        'label' => 'Property Name',
    ])->attributes)
    ->toHaveKeys(['name', 'label'])
    ->toHaveKey('label', 'Property Name')
    ->group('attributes', 'fields');

it('should overwrite the name attribute')
    ->expect(fn () => Field::make(null, 'property_title')->attributes([
        'name' => 'property',
    ])->attributes)
    ->toHaveKeys(['name', 'label'])
    ->toHaveKey('name', 'property')
    ->group('attributes', 'fields');

it('should not be sortable by default')
    ->expect(fn () => Field::make(null, 'id')->attributes)
    ->toHaveKey('sortable', false)
    ->group('attributes', 'fields');

it('should not be searchable by default')
    ->expect(fn () => Field::make(null, 'id')->attributes)
    ->toHaveKey('searchable', false)
    ->group('attributes', 'fields');

it('should not be constrainable by default')
    ->expect(fn () => Field::make(null, 'id')->attributes)
    ->toHaveKey('constrainable', false)
    ->group('attributes', 'fields');

it('should not be filterable by default')
    ->expect(fn () => Field::make(null, 'id')->attributes)
    ->toHaveKey('filterable', false)
    ->group('attributes', 'fields');

it('should not be hidden by default')
    ->expect(fn () => Field::make(null, 'id')->attributes)
    ->toHaveKey('hidden', false)
    ->group('attributes', 'fields');

it('should not be selectable by default')
    ->expect(fn () => Field::make(null, 'id')->attributes)
    ->toHaveKey('selectable', false)
    ->group('attributes', 'fields');

it('should not be copyable by default')
    ->expect(fn () => Field::make(null, 'id')->attributes)
    ->toHaveKey('copyable', false)
    ->group('attributes', 'fields');

it('should not be copyable')
    ->expect(fn () => Field::make(null, 'id')->copyable()->attributes)
    ->toHaveKey('copyable', true)
    ->group('attributes', 'fields');

it('should not be readonlye')
    ->expect(fn () => Field::make(null, 'id')->readonly()->attributes)
    ->toHaveKey('readonly', true)
    ->group('attributes', 'fields');


it('should be sortable')
    ->expect(fn () => Field::make(null, 'id')->sortable()->attributes)
    ->toHaveKey('sortable', true)
    ->group('attributes', 'fields');

it('should be selectable')
    ->expect(fn () => Field::make(null, 'id')->selectable()->attributes)
    ->toHaveKey('selectable', true)
    ->group('attributes', 'fields');

it('should be hidden for index context')
    ->expect(fn () => Field::make(null, 'id')->hidden()->toAttributes(Field::CONTEXT_INDEX))
    ->toHaveKey('attributes.hidden', true)
    ->group('attributes', 'fields');

it('should be hidden for detail context')
    ->expect(fn () => Field::make(null, 'id')->hidden()->toAttributes(Field::CONTEXT_DETAIL))
    ->toHaveKey('attributes.hidden', true)
    ->group('attributes', 'fields');


it('should have an array with attributes')
    ->expect(fn () => Field::make(null, 'created_at')
        ->context(Field::CONTEXT_INDEX)
        ->toAttributes())
    ->toHaveKeys(['component', 'panel', 'attributes', 'render', 'key'])
    ->group('attributes', 'fields');
