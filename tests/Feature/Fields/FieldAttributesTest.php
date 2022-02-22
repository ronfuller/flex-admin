<?php

use Psi\FlexAdmin\Fields\Enums\DisplayContext;
use Psi\FlexAdmin\Fields\Field;

it('should set a default camel case name')
    ->expect(fn () => Field::make(null, 'property_title')->meta)
    ->toHaveKey('name', 'propertyTitle')
    ->toHaveKey('field', 'propertyTitle')
    ->group('attributes', 'fields');

it('should set a default title case label')
    ->expect(fn () => Field::make(null, 'property_title')->meta)
    ->toHaveKey('label', 'Property Title')
    ->group('attributes', 'fields');

it('should merge custom attributes')
    ->expect(fn () => Field::make(null, 'property_title')->icon('mdi-account')->attributes([
        'align' => 'left',
        'url' => 'https://www.google.com',
    ])->attributes)
    ->toHaveKeys(['icon', 'align', 'url'])
    ->group('attributes', 'fields');

it('should overwrite the label attribute')
    ->expect(fn () => Field::make(null, 'property_title')->meta([
        'label' => 'Property Name',
    ])->meta)
    ->toHaveKeys(['name', 'label'])
    ->toHaveKey('label', 'Property Name')
    ->group('attributes', 'fields');

it('should overwrite the name attribute')
    ->expect(fn () => Field::make(null, 'property_title')->meta([
        'name' => 'property',
    ])->meta)
    ->toHaveKeys(['name', 'label'])
    ->toHaveKey('name', 'property')
    ->group('attributes', 'fields');

it('should not be sortable by default')
    ->expect(fn () => Field::make(null, 'id')->meta)
    ->toHaveKey('sortable', false)
    ->group('attributes', 'fields');

it('should not be searchable by default')
    ->expect(fn () => Field::make(null, 'id')->meta)
    ->toHaveKey('searchable', false)
    ->group('attributes', 'fields');

it('should not be constrainable by default')
    ->expect(fn () => Field::make(null, 'id')->meta)
    ->toHaveKey('constrainable', false)
    ->group('attributes', 'fields');

it('should not be filterable by default')
    ->expect(fn () => Field::make(null, 'id')->meta)
    ->toHaveKey('filterable', false)
    ->group('attributes', 'fields');

it('should not be hidden by default')
    ->expect(fn () => Field::make(null, 'id')->meta)
    ->toHaveKey('hidden', false)
    ->group('attributes', 'fields');

it('should not be selectable by default')
    ->expect(fn () => Field::make(null, 'id')->meta)
    ->toHaveKey('selectable', false)
    ->group('attributes', 'fields');

it('should not be copyable by default')
    ->expect(fn () => Field::make(null, 'id')->meta)
    ->toHaveKey('copyable', false)
    ->group('attributes', 'fields');

it('should not be copyable')
    ->expect(fn () => Field::make(null, 'id')->copyable()->meta)
    ->toHaveKey('copyable', true)
    ->group('attributes', 'fields');

it('should not be readonlye')
    ->expect(fn () => Field::make(null, 'id')->readonly()->meta)
    ->toHaveKey('readonly', true)
    ->group('attributes', 'fields');


it('should be sortable')
    ->expect(fn () => Field::make(null, 'id')->sortable()->meta)
    ->toHaveKey('sortable', true)
    ->group('attributes', 'fields');

it('should be selectable')
    ->expect(fn () => Field::make(null, 'id')->selectable()->meta)
    ->toHaveKey('selectable', true)
    ->group('attributes', 'fields');

it('should have an array with attributes')
    ->expect(fn () => Field::make(null, 'created_at')
        ->context(DisplayContext::INDEX->value)
        ->icon('mdi-account')
        ->toAttributes())
    ->toHaveKey('icon')
    ->group('attributes', 'fields');
