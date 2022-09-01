<?php

use Illuminate\Http\Request;
use Psi\FlexAdmin\Enums\ControlParams;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Resources\ApplicationGroupResource;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;
use Psi\FlexAdmin\Tests\Models\Property;

it('should create a resource')
    ->expect(fn () => new PropertyResource(Property::first()))
    ->not->toBeNull()
    ->group('resources');

it('should return an array limited by valid keys')
    ->expect(fn () => (new PropertyResource(Property::first()))
        ->withContext(Field::CONTEXT_INDEX)
        // With keys prevents unneccessary field processing
        ->withKeys(['id', 'name'])
        ->toArray(Request::create('http://test.com')))
    ->toBeArray()
    ->group('resources');

it('should return fields')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->toFields())
    ->toHaveKeys(['uuid', 'propertyId', 'name', 'createdAt', 'color', 'status', 'type', 'companyId', 'companyName', 'companyEmployees'])
    ->group('resources');

it('should return an id field')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->toFields())
    ->toHaveKey('propertyId')
    ->group('resources');

it('should return a text field')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property())['columns'][1])
    ->toHaveKey('key', 'name')
    ->toHaveKey('render', true)
    ->toHaveKey('panel', 'details')
    ->toHaveKey('component', 'text-field')
    ->toHaveKeys(['render', 'component', 'panel', 'addToValues'])
    ->group('resources');

it('should return a text field value and attributes')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->toArray(createRequest()))
    ->toHaveKey('name.value', 'Test Property')
    ->group('resources');

it('should create a resource permission')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->wrapResourcePermission('view'))
    ->toBe('properties.view')
    ->group('resources');

it('should create a resource permission for edit action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourcePermission('edit'))
    ->toBe('application-groups.edit')
    ->group('resources');

it('should get resource controls')
    ->expect(fn () => (new PropertyResource($this->property))->getControls())
    ->toHaveKeys(ControlParams::values());

it('should get default resource controls')
    ->expect(fn () => (new PropertyResource($this->property))->getControls())
    ->toHaveKey('defaultActions', ['view', 'edit', 'create', 'delete']);

it('should get empty default actions resource controls')
    ->expect(fn () => (new PropertyResource($this->property))->withoutDefaultActions()->getControls())
    ->toHaveKey('defaultActions', []);

it('should set default actions resource controls')
    ->expect(fn () => (new PropertyResource($this->property))->setControls(['defaultActions' => []])->getControls())
    ->toHaveKey('defaultActions', []);
