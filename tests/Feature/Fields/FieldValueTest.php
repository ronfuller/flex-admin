<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have a matching date casted value')
    ->expect(fn () => Field::make(null, 'created_at')
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBe('01/19/2020 12:00:01 PM')
    ->group('value', 'fields');

it('should have an array value')
    ->expect(fn () => Field::make(null, 'created')
        ->value(['created_at', 'name'])
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBe(['created_at' => '01/19/2020 12:00:01 PM', 'name' => 'Test Property'])
    ->group('value', 'fields');

it('should create an array with attributes and value')
    ->expect(fn () => Field::make(null, 'created')
        ->value('name')
        ->model($this->property)
        ->toArray($this->property->attributesToArray()))
    ->toHaveKey('value', 'Test Property')
    ->group('value', 'fields');

it('should have a callable value')
    ->expect(fn () => Field::make(null, 'created')
        ->value(fn ($model) => $model->created_at->format('Y-m-d'))
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBe('2020-01-19')
    ->group('value', 'fields');

it('should have a default value')
    ->expect(fn () => Field::make(null, 'null_column')
        ->default('test')
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBe('test')
    ->group('value', 'fields');

it('should have a null value')
    ->expect(fn () => Field::make(null, 'null_column')
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBeNull()
    ->group('value', 'fields');

it('should have a value only')
    ->expect(fn () => Field::make(null, 'id')->valueOnly()->model(new Property())->toMeta())
    ->toHaveKey('render', false)
    ->toHaveKey('component', null)
    ->toHaveKey('panel', '')
    ->group('value', 'fields');

it('should be added to resource values')
    ->expect(fn () => Field::make(null, 'id')->addToValues()->model(new Property())->toMeta())
    ->toHaveKey('addToValues', true)
    ->group('value', 'fields');

it('should not be added to resource values by default')
    ->expect(fn () => Field::make(null, 'id')->model(new Property())->toMeta())
    ->toHaveKey('addToValues', false)
    ->group('value', 'fields');

it('should be added to resource values if value only')
    ->expect(fn () => Field::make(null, 'id')->valueOnly()->model(new Property())->toMeta())
    ->toHaveKey('addToValues', true)
    ->group('value', 'fields');

it('should validate the column when value only')
    ->expect(fn () => Field::make(null, 'id')->valueOnly()->model(new Property())->toMeta())
    ->toBeArray()
    ->group('value', 'fields');
