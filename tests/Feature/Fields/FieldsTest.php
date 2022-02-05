<?php


use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;
use Psi\FlexAdmin\Tests\Models\User;

beforeEach(function () {
    $this->property = Property::factory()->create(
        [
            'created_at' => '2020-01-19 12:00:01',
            'name' => 'Test Property',
        ]
    );
    $this->user = User::factory()->create(
        [
            'permissions' => ['properties.view-any'],
        ]
    );
    actingAs($this->user);
});


it('should make a resource field')
    ->expect(Field::make(null, 'id'))
    ->not()
    ->toBeNull();

it('should be null if resource field is not in column list')
    ->expect(Field::make(['name'], 'id'))
    ->toBeNull();

it('should create display defaults')
    ->expect(Field::make(null, 'id')->display)
    ->toBe([
        Field::CONTEXT_INDEX => true,
        Field::CONTEXT_DETAIL => true,
        Field::CONTEXT_EDIT => true,
        Field::CONTEXT_CREATE => true,
    ])
    ->group('display');

it('should have default permissions')
    ->expect(Field::make(null, 'id')->permissions)
    ->not->toBeNull()
    ->group('permissions');

it('should hide field from index')
    ->expect(Field::make(null, 'id')->hideFromIndex()->display)
    ->toHaveKey(Field::CONTEXT_INDEX, false)
    ->toHaveKey(Field::CONTEXT_DETAIL, true)
    ->group('display');

it('should hide field from detail')
    ->expect(Field::make(null, 'id')->hideFromDetail()->display)
    ->toHaveKey(Field::CONTEXT_DETAIL, false)
    ->toHaveKey(Field::CONTEXT_INDEX, true)
    ->group('display');

it('should hide field from create')
    ->expect(Field::make(null, 'id')->hideFromCreate()->display)
    ->toHaveKey(Field::CONTEXT_CREATE, false)
    ->toHaveKey(Field::CONTEXT_INDEX, true)
    ->group('display');

it('should hide field from edit')
    ->expect(Field::make(null, 'id')->hideFromEdit()->display)
    ->toHaveKey(Field::CONTEXT_EDIT, false)
    ->toHaveKey(Field::CONTEXT_DETAIL, true)
    ->toHaveKey(Field::CONTEXT_INDEX, true)
    ->group('display');

it('should only have index context')
    ->expect(Field::make(null, 'id')->indexOnly()->display)
    ->toHaveKey(Field::CONTEXT_INDEX, true)
    ->not->toHaveKeys([Field::CONTEXT_DETAIL, Field::CONTEXT_CREATE, Field::CONTEXT_EDIT])
    ->group('display');

it('should only have detail context')
    ->expect(Field::make(null, 'id')->detailOnly()->display)
    ->toHaveKey(Field::CONTEXT_DETAIL, true)
    ->not->toHaveKeys([Field::CONTEXT_INDEX, Field::CONTEXT_CREATE, Field::CONTEXT_EDIT])
    ->group('display');

it('should only have edit context')
    ->expect(Field::make(null, 'id')->editOnly()->display)
    ->toHaveKey(Field::CONTEXT_EDIT, true)
    ->not->toHaveKeys([Field::CONTEXT_INDEX, Field::CONTEXT_CREATE, Field::CONTEXT_DETAIL])
    ->group('display');

it('should only have create context')
    ->expect(Field::make(null, 'id')->createOnly()->display)
    ->toHaveKey(Field::CONTEXT_CREATE, true)
    ->not->toHaveKeys([Field::CONTEXT_INDEX, Field::CONTEXT_EDIT, Field::CONTEXT_DETAIL])
    ->group('display');

it('should set a default camel case name')
    ->expect(Field::make(null, 'property_title')->attributes)
    ->toHaveKey('name', 'propertyTitle')
    ->group('attributes');

it('should set a default title case label')
    ->expect(Field::make(null, 'property_title')->attributes)
    ->toHaveKey('label', 'Property Title')
    ->group('attributes');

it('should merge custom attributes')
    ->expect(Field::make(null, 'property_title')->attributes([
        'align' => 'left',
        'url' => 'https://www.google.com',
    ])->attributes)
    ->toHaveKeys(['name', 'label', 'align', 'url'])
    ->group('attributes');

it('should overwrite the label attribute')
    ->expect(Field::make(null, 'property_title')->attributes([
        'label' => 'Property Name',
    ])->attributes)
    ->toHaveKeys(['name', 'label'])
    ->toHaveKey('label', 'Property Name')
    ->group('attributes');

it('should overwrite the name attribute')
    ->expect(Field::make(null, 'property_title')->attributes([
        'name' => 'property',
    ])->attributes)
    ->toHaveKeys(['name', 'label'])
    ->toHaveKey('name', 'property')
    ->group('attributes');

it('should not be sortable by default')
    ->expect(Field::make(null, 'id')->attributes)
    ->toHaveKey('sortable', false)
    ->group('attributes');

it('should not be searchable by default')
    ->expect(Field::make(null, 'id')->attributes)
    ->toHaveKey('searchable', false)
    ->group('attributes');

it('should not be constrainable by default')
    ->expect(Field::make(null, 'id')->attributes)
    ->toHaveKey('constrainable', false)
    ->group('attributes');

it('should not be filterable by default')
    ->expect(Field::make(null, 'id')->attributes)
    ->toHaveKey('filterable', false)
    ->group('attributes');

it('should not be hidden by default')
    ->expect(Field::make(null, 'id')->attributes)
    ->toHaveKey('hidden', false)
    ->group('attributes');

it('should not be selectable by default')
    ->expect(Field::make(null, 'id')->attributes)
    ->toHaveKey('selectable', false)
    ->group('attributes');

it('should not be copyable by default')
    ->expect(Field::make(null, 'id')->attributes)
    ->toHaveKey('copyable', false)
    ->group('attributes');

it('should be filterable')
    ->expect(Field::make(null, 'id')->filterable()->attributes)
    ->toHaveKey('filterable', true)
    ->group('attributes');

it('should be searchable')
    ->expect(Field::make(null, 'id')->searchable()->attributes)
    ->toHaveKey('searchable', true)
    ->group('attributes');

it('should be sortable')
    ->expect(Field::make(null, 'id')->sortable()->attributes)
    ->toHaveKey('sortable', true)
    ->group('attributes');

it('should be selectable')
    ->expect(Field::make(null, 'id')->selectable()->attributes)
    ->toHaveKey('selectable', true)
    ->group('attributes');

it('should be hidden for index context')
    ->expect(Field::make(null, 'id')->hidden()->toAttributes(Field::CONTEXT_INDEX))
    ->toHaveKey('attributes.hidden', true)
    ->group('attributes');

it('should be hidden for detail context')
    ->expect(Field::make(null, 'id')->hidden()->toAttributes(Field::CONTEXT_DETAIL))
    ->toHaveKey('attributes.hidden', true)
    ->group('attributes');

it('should have a panel')
    ->expect(Field::make(null, 'id')->panel('properties')->toAttributes(Field::CONTEXT_INDEX))
    ->toHaveKey('panel', 'properties')
    ->group('render');

it('should have default render properties')
    ->expect(Field::make(null, 'id')->toAttributes(Field::CONTEXT_INDEX))
    ->toHaveKey('panel', 'details')
    ->group('render');


it('should have a column')
    ->expect(fn () => Field::make(null, 'id')->model(new Property())->context(Field::CONTEXT_INDEX)->toColumn())
    ->toHaveKeys(['component', 'render', 'label', 'enabled', 'key', 'name', 'sortable', 'searchable', 'constrainable', 'select', 'align', 'sort', 'defaultSort', 'sortDir']);


it('should have a column hidden from index')
    ->expect(fn () => Field::make(null, 'id')->hideFromIndex()->model(new Property())->context(Field::CONTEXT_INDEX)->toColumn())
    ->toHaveKey('enabled', false)
    ->group('display');


it('should have a constrained column')
    ->expect(fn () => Field::make(null, 'id')->constrainable()->model(new Property())->context(Field::CONTEXT_INDEX)->toColumn())
    ->toHaveKey('constrainable', true);

it('should have a column hidden from detail')
    ->expect(fn () => Field::make(null, 'id')->indexOnly()->model(new Property())->context(Field::CONTEXT_DETAIL)->toColumn())
    ->toHaveKey('enabled', false)
    ->group('display');

it('should have a column that is the default sort')
    ->expect(fn () => Field::make(null, 'id')->defaultSort('asc')->model(new Property())->toColumn())
    ->toHaveKey('defaultSort', true)
    ->toHaveKey('sortDir', 'asc')
    ->group('sort');

it('should validate the sort direction', function () {
    expect(fn () => Field::make(null, 'id')->defaultSort('invalid'))
        ->toThrow('Error in sort direction parameter');
})->group('sort');

it('should validate the column when value only')
    ->expect(fn () => Field::make(null, 'id')->valueOnly()->model(new Property())->toColumn())
    ->toBeArray();

it('should have a qualified select')
    ->expect(fn () => Field::make(null, 'id')->model(new Property())->toColumn())
    ->toHaveKey('select', 'properties.id')
    ->group('select');

it('should have a qualified select for a json column')
    ->expect(fn () => Field::make(null, 'color')->select('option->color')->model(new Property())->toColumn())
    ->toHaveKey('select', 'properties.option->color as color')
    ->group('select');

it('should have a column with null select when key is not in table')
    ->expect(fn () => Field::make(null, 'no_column')->model(new Property())->toColumn())
    ->toHaveKey('select', null)
    ->group('select');


it('should have an array with attributes')
    ->expect(fn () => Field::make(null, 'created_at')
        ->context(Field::CONTEXT_INDEX)
        ->toAttributes())
    ->toHaveKeys(['component', 'panel', 'attributes', 'render', 'key'])
    ->group('attributes');

it('should have enabled attributes false when hidden from index')
    ->expect(fn () => Field::make(null, 'created_at')
        ->hideFromIndex()
        ->context(Field::CONTEXT_INDEX)
        ->toAttributes())
    ->toHaveKey('attributes.enabled', false)
    ->group('display');

it('should have enabled attribute false when index only')
    ->expect(fn () => Field::make(null, 'created_at')
        ->indexOnly()
        ->context(Field::CONTEXT_DETAIL)
        ->toAttributes())
    ->toHaveKey('attributes.enabled', false)
    ->group('display');

it('should have enabled attribute false when detail only')
    ->expect(fn () => Field::make(null, 'created_at')
        ->detailOnly()
        ->context(Field::CONTEXT_INDEX)
        ->toAttributes())
    ->toHaveKey('attributes.enabled', false)
    ->group('display');

it('should have enabled attribute false when create only')
    ->expect(fn () => Field::make(null, 'created_at')
        ->createOnly()
        ->context(Field::CONTEXT_INDEX)
        ->toAttributes())
    ->toHaveKeys(['component', 'panel', 'attributes', 'key', 'render'])
    ->toHaveKey('attributes.enabled', false)
    ->group('display');

it('should have a matching date casted value')
    ->expect(fn () => Field::make(null, 'created_at')
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBe('01/19/2020 12:00:01 PM')
    ->group('value');

it('should have an array value')
    ->expect(fn () => Field::make(null, 'created')
        ->value(['created_at', 'name'])
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBe(['created_at' => '01/19/2020 12:00:01 PM', 'name' => 'Test Property'])
    ->group('value');

it('should have create an array with attributes and value')
    ->expect(fn () => Field::make(null, 'created')
        ->value('name')
        ->model($this->property)
        ->toArray($this->property->attributesToArray()))
    ->toHaveKeys(['render', 'component', 'key', 'panel', 'attributes', 'value'])
    ->toHaveKey('value', 'Test Property')
    ->group('value');

it('should have a callable value')
    ->expect(fn () => Field::make(null, 'created')
        ->value(fn ($model) => $model->created_at->format('Y-m-d'))
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBe('2020-01-19')
    ->group('value');

it('should have a default value')
    ->expect(fn () => Field::make(null, 'null_column')
        ->default('test')
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBe('test')
    ->group('value');

it('should have a null value')
    ->expect(fn () => Field::make(null, 'null_column')
        ->model($this->property)
        ->toValue($this->property->attributesToArray()))
    ->toBeNull()
    ->group('value');

it('should have a value only')
    ->expect(fn () => Field::make(null, 'id')->valueOnly()->toAttributes(Field::CONTEXT_INDEX))
    ->toHaveKey('render', false)
    ->toHaveKey('component', null)
    ->toHaveKey('panel', '')
    ->group('value');

it('should have be added to resource values')
    ->expect(fn () => Field::make(null, 'id')->addToValues()->toAttributes(Field::CONTEXT_INDEX))
    ->toHaveKey('addToValues', true)
    ->group('value');

it('should have permission to index the resource')
    ->expect(fn () => Field::make(null, 'id')->withPermissions(Field::CONTEXT_INDEX, new Property())->model(new Property())->toColumn())
    ->toHaveKey('enabled', true)
    ->group('permissions');

it('should not have permission to edit the resource')
    ->expect(fn () => Field::make(null, 'id')->withPermissions(Field::CONTEXT_EDIT, new Property())->model(new Property())->toColumn())
    ->toHaveKey('enabled', false)
    ->group('permissions');

it('should have permission to edit the resource with permissions disabled')
    ->expect(fn () => Field::make(null, 'id')->withoutPermissions()->withPermissions(Field::CONTEXT_EDIT, new Property())->model(new Property())->toColumn())
    ->toHaveKey('enabled', true)
    ->group('permissions');

it('should have a searchable type')
    ->expect(fn () => Field::make(null, 'name')
        ->searchable('full')
        ->model(new Property())
        ->toColumn())
    ->toHaveKey('searchType', 'full');

it('should have a default searchable type')
    ->expect(fn () => Field::make(null, 'name')
        ->model(new Property())
        ->toColumn())
    ->toHaveKey('searchType', 'full');

it('should throw error on invalid searchable type', function () {
    expect(fn () => Field::make(null, 'name')
        ->searchable('invalid'))
        ->toThrow("Invalid search type");
});

it('should have constraints')
    ->expect(fn () => Field::make(null, 'id')->constrainable()->model(new Property())->toColumn())
    ->toHaveKey('constrainable', true);

it('should have a related model')
    ->expect(fn () =>
    Field::make(null, 'companyName')
        ->on(Company::class)
        ->select('name')
        ->model(new Property())
        ->toColumn())
    ->toBeArray()
    ->select
    ->toBe('companies.name as companyName')
    ->column
    ->toBe('companies.name')
    ->sort
    ->toBe('companies.name');

it('should have a related model join')
    ->expect(fn () =>
    Field::make(null, 'companyName')
        ->on(Company::class)
        ->select('name')
        ->model(new Property())
        ->toColumn())
    ->toBeArray()
    ->join
    ->toBe(['companies', 'companies.id', '=', 'properties.company_id']);

it('should have a detail context specific component')
    ->expect(fn () => Field::make(null, 'name')->detailComponent('chip-field')->model(new Property())->context(Field::CONTEXT_DETAIL)->toColumn())
    ->component
    ->toBe('chip-field');

it('should have an edit context specific component')
    ->expect(fn () => Field::make(null, 'name')->editComponent('chip-field')->model(new Property())->context(Field::CONTEXT_DETAIL)->toColumn())
    ->component
    ->toBe('text-field');

it('should have a create context specific component')
    ->expect(fn () => Field::make(null, 'name')->createComponent('chip-field')->model(new Property())->context(Field::CONTEXT_CREATE)->toColumn())
    ->component
    ->toBe('chip-field');

it('should have an index context specific component')
    ->expect(fn () => Field::make(null, 'name')->indexComponent('chip-field')->model(new Property())->context(Field::CONTEXT_INDEX)->toColumn())
    ->component
    ->toBe('chip-field');
