<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Controllers\TestController;
use Psi\FlexAdmin\Tests\Http\Resources\ApplicationGroupResource;
use Psi\FlexAdmin\Tests\Http\Resources\CompanyResource;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;
use Psi\FlexAdmin\Tests\Models\ApplicationGroup;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;
use Psi\FlexAdmin\Tests\Models\User;

beforeEach(function () {
    $this->property = Property::factory()->create(
        [
            'name' => 'Test Property',
        ]
    );
    $this->applicationGroup = ApplicationGroup::factory()->make();
    $this->user = User::factory()->create(
        [
            'permissions' => ['properties.view-any', 'properties.view', 'properties.edit', 'properties.create', 'properties.delete'],
        ]
    );
    actingAs($this->user);
    Route::resource('properties', TestController::class);
});

it('should create a resource')
    ->expect(fn () => new PropertyResource(Property::first()))
    ->not->toBeNull();

it('should return an array limited by valid keys')
    ->expect(fn () => (new PropertyResource(Property::first()))
        ->withContext(Field::CONTEXT_INDEX)
        ->withKeys(['id', 'name'])
        ->toArray(Request::create('http://test.com')))
    ->toBeArray()
    ->toHaveKeys(['fields', 'values', 'actions']);


it('should return collection meta')
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
    ]);

it('should return collection meta with searches')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->searches
    ->toHaveCount(3)
    ->toHaveKey('0.key', 'name');

it('should return collection meta with constraints')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->constraints
    ->toHaveCount(3)
    ->toHaveKey('0.name', 'propertyId');

it('should return collection meta with joins')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->joins
    ->toHaveCount(1)
    ->each
    ->toHaveCount(4);

it('should return collection meta without joins')
    ->expect(fn () => (new CompanyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Company()))
    ->joins
    ->toHaveCount(0);

it('should return default pagination')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->perPage
    ->toBe(15);

it('should set pagination')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->setPerPage(20)
        ->toMeta(new Property()))
    ->perPage
    ->toBe(20);

it('should get default per page options')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->perPageOptions
    ->toMatchArray([5, 15, 25, 50, 75, 100]);

it('should set default per page options')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->setPerPageOptions([25, 50])
        ->toMeta(new Property()))
    ->perPageOptions
    ->toMatchArray([25, 50]);


it('should return fields')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->toFields())
    ->toHaveCount(9);

it('should return an id field')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->toFields()[0])
    ->toHaveKey('key', 'id')
    ->toHaveKey('render', false)
    ->toHaveKey('component', null)
    ->toHaveKeys(['render', 'component', 'panel', 'attributes', 'addToValues', 'value']);

it('should return a text field')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->toFields()[1])
    ->toHaveKey('key', 'name')
    ->toHaveKey('render', true)
    ->toHaveKey('panel', 'details')
    ->toHaveKey('component', 'text-field')
    ->toHaveKey('value', 'Test Property')
    ->toHaveKeys(['render', 'component', 'panel', 'attributes', 'addToValues', 'value']);


it('should create a resource route for view action')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->wrapResourceRoute('view'))
    ->toHaveKey('0', 'properties.show')
    ->toHaveKey('2.property');

it('should create a resource route for view action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourceRoute('view'))
    ->toHaveKey('0', 'application-groups.show')
    ->toHaveKey('2.application_group');

it('should create a resource route for delete action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourceRoute('delete'))
    ->toHaveKey('0', 'application-groups.destroy')
    ->toHaveKey('2.application_group');

it('should create a resource route for edit action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourceRoute('edit'))
    ->toHaveKey('0', 'application-groups.edit')
    ->toHaveKey('2.application_group');

it('should create a resource title for edit action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourceTitle('edit'))
    ->toBe("Edit Application Group");

it('should create a resource title for delete action for a property resource')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->wrapResourceTitle('delete'))
    ->toBe("Delete Property");

it('should create a resource permission')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->wrapResourcePermission('view'))
    ->toBe("properties.view");

it('should create a resource permission for edit action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourcePermission('edit'))
    ->toBe("application-groups.edit");

it('should create default actions', function () {
    $results = (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->toArray(createRequest());
    expect($results['actions'])->toHaveCount(3)
        ->toHaveKey('0.slug', 'view')
        ->toHaveKey('1.slug', 'edit')
        ->toHaveKey('2.slug', 'delete');
});

it('should have a default details panel')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_DETAIL)->toArray(createRequest()))
    ->toHaveKey('panels');

it('should add fields to default details panel')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_DETAIL)->toArray(createRequest()))
    ->toHaveKey('panels.0.fields', ['name', 'created_at', 'updated_at', 'color', 'type', 'companyName', 'companyEmployees']);


it('should have empty panels without panels')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_DETAIL)->withoutPanels()->toArray(createRequest()))
    ->toHaveKey('panels', []);

it('should hide panels without fields')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_DETAIL)->toArray(createRequest()))
    ->panels
    ->toHaveCount(1);

it('should have not have panels in index context')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->toArray(createRequest()))
    ->not
    ->toHaveKey('panels');

it('should have filters')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->toMeta(new Property()))
    ->filters
    ->toHaveCount(3);

it('should have empty filters when without filters is set')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->withoutFilters()->toMeta(new Property()))
    ->filters
    ->toBe([]);

it('should create filters with meta information')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->toMeta(new Property())['filters'])
    ->each(fn ($filter) => $filter->toArray()->toHaveKey('meta.column'));
