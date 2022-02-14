<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Resources\ApplicationGroupResource;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;

it('should create a resource route for view action')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->wrapResourceRoute('view'))
    ->sequence(
        fn ($name) => $name->toBe('properties.show'),
        fn ($method) => $method->toBe("get"),
        fn ($params) => $params->toBeArray()
    )->group('resources', 'action');


it('should create a resource route for view action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourceRoute('view'))
    ->toHaveKey('0', 'application-groups.show')
    ->toHaveKey('2', [['name' => 'application_group', 'field' => 'id']])
    ->group('resources', 'action');


it('should create a resource route for delete action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourceRoute('delete'))
    ->toHaveKey('0', 'application-groups.destroy')
    ->toHaveKey('1', 'delete')
    ->toHaveKey('2', [['name' => 'application_group', 'field' => 'id']])
    ->group('resources', 'action');


it('should create a resource route for edit action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourceRoute('edit'))
    ->toHaveKey('0', 'application-groups.edit')
    ->toHaveKey('2', [['name' => 'application_group', 'field' => 'id']])
    ->group('resources', 'action');


it('should create a resource title for edit action for an application group resource')
    ->expect(fn () => (new ApplicationGroupResource($this->applicationGroup))->withContext(Field::CONTEXT_INDEX)->wrapResourceTitle('edit'))
    ->toBe("Edit Application Group")
    ->group('resources', 'action');


it('should create a resource title for delete action for a property resource')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->wrapResourceTitle('delete'))
    ->toBe("Delete Property")
    ->group('resources', 'action');

it('should create default actions', function () {
    $results = (new PropertyResource($this->property))->withContext(Field::CONTEXT_INDEX)->toArray(createRequest());
    expect($results['actions'])->toHaveCount(3)
        ->toHaveKey('0.slug', 'view')
        ->toHaveKey('1.slug', 'edit')
        ->toHaveKey('2.slug', 'delete');
})
    ->group('resources', 'action');

it('should set default actions', function () {
    $results = (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->withDefaultActions(['view'])
        ->toArray(createRequest());
    expect($results['actions'])->toHaveCount(1)
        ->toHaveKey('0.slug', 'view');
})
    ->group('resources', 'action');


it('should create edit,view only default actions', function () {
    $results = (new ApplicationGroupResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->withDefaultActions(['view', 'edit'])
        ->toArray(createRequest());
    expect($results['actions'])->toHaveCount(2)
        ->toHaveKey('0.slug', 'view')
        ->toHaveKey('1.slug', 'edit');
})
    ->group('resources', 'action');


it('should throw error on invalid set default actions', function () {
    expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_INDEX)
        ->withDefaultActions(['invalid']))
        ->toThrow("Invalid default actions.");
})
    ->group('resources', 'action');


it('should return with empty actions', function () {
    $results = (new PropertyResource($this->property))->withoutActions()->withContext(Field::CONTEXT_INDEX)->toArray(createRequest());
    expect($results['actions'])->toHaveCount(0);
})
    ->group('resources', 'action');;
