<?php


use Illuminate\Http\Request;

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Resources\ApplicationGroupResource;
use Psi\FlexAdmin\Tests\Http\Resources\CompanyResource;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;

it('should create a resource')
    ->expect(fn () => new PropertyResource(Property::first()))
    ->not->toBeNull()
    ->group('resources');

it('should return an array limited by valid keys')
    ->expect(fn () => (new PropertyResource(Property::first()))
        ->withContext(Field::CONTEXT_INDEX)
        ->withKeys(['id', 'name'])
        ->toArray(Request::create('http://test.com')))
    ->toBeArray()
    ->group('resources');

it('should return collection meta')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->toBeArray()
    ->group('resources');

it('should return collection meta columns')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->columns
    ->each
    ->toHaveKeys([
        'enabled',
        'sortable',
        'filterable',
        'constrainable',
        'searchable',
        'selectable',
        'hidden',
        'align',
        'name',
        'label',
        'component',
        'key',
        'select',
        'sort',
        'column',
        'defaultSort',
        'sortDir',
        'searchType',
        'filterType',
        'addToValues',
        'join',
    ])
    ->group('resources');

it('should return collection columns in array list')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->columns
    ->toBeArrayList()
    ->group('resources');

it('should return all columns including non-renderable')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->columns
    ->toHaveKey("0.name", "propertyId")
    ->toHaveKey("0.render", false)
    ->group('resources');

it('should return collection meta with searches')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->searches
    ->toHaveCount(4)
    ->toHaveKey('0.key', 'name')
    ->group('resources');

it('should return collection meta with constraints')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->constraints
    ->toHaveCount(4)
    ->toHaveKey('0.name', 'propertyId')
    ->group('resources');

it('should return collection meta with joins')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->joins
    ->toHaveCount(1)
    ->each
    ->toHaveCount(4)
    ->group('resources');

it('should return collection meta without joins')
    ->expect(fn () => (new CompanyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Company()))
    ->joins
    ->toHaveCount(0)
    ->group('resources');

it('should return fields')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->toFields())
    ->toHaveKeys(['uuid', 'propertyId', 'name', 'createdAt', 'color', 'status', 'type', 'company', 'companyName', 'companyEmployees'])
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
    ->toBe("properties.view")
    ->group('resources');

it('should create a resource permission for edit action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourcePermission('edit'))
    ->toBe("application-groups.edit")
    ->group('resources');
