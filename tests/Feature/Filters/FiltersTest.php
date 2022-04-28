<?php

use Psi\FlexAdmin\Filters\Filter;
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

it('should make a resource filter')
    ->expect(fn () => Filter::make('company'))
    ->not()
    ->toBeNull()
    ->group('filters');

it('should have a filter name')
    ->expect(fn () => Filter::make('company')->toArray())
    ->name
    ->toBe('company')
    ->group('filters');

it('should have a default type of filter')
    ->expect(fn () => Filter::make('company')->toArray()['type'])
    ->toBe('select')
    ->group('filters');

it('should have a default label')
    ->expect(fn () => Filter::make('company')->toArray())
    ->label
    ->toBe('Company')
    ->group('filters');

it('should have a default label from snake case')
    ->expect(fn () => Filter::make('company_user')->toArray())
    ->label
    ->toBe('Company User')
    ->group('filters');

it('should have a default label from kebab case')
    ->expect(fn () => Filter::make('company-user')->toArray())
    ->label
    ->toBe('Company User')
    ->group('filters');

it('should have a default label from dot notation')
    ->expect(fn () => Filter::make('company.admin_user')->toArray())
    ->label
    ->toBe('Admin User')
    ->group('filters');

it('should have a label')
    ->expect(fn () => Filter::make('company.admin_user')->label('My Label')->toArray())
    ->label
    ->toBe('My Label')
    ->group('filters');

it('should have an icon')
    ->expect(fn () => Filter::make('company')->icon('mdi-domain')->toArray())
    ->icon
    ->toBe('mdi-domain')
    ->group('filters');

it('should have an option configuration')
    ->expect(fn () => Filter::make('company')->option('name', 'id')->toArray())
    ->toHaveKey('optionValue', 'id')
    ->toHaveKey('optionLabel', 'name')
    ->group('filters');

it('should append attributes')
    ->expect(fn () => Filter::make('company')->attributes(
        [
            'dense' => true,
            'optionsDense' => true,
        ]
    )->toArray())
    ->toHaveKeys(['label', 'name', 'dense', 'optionsDense'])
    ->group('filters');

it('should throw exception appending reserved keys', function () {
    expect(fn () => Filter::make('company')->attributes(
        [
            'name' => 'test',
            'label' => 'test label',
        ]
    ))
        ->toThrow('Cannot append attributes');
})
    ->group('filters');

it('should set filter type to boolean')
    ->expect(fn () => Filter::make('company')->boolean()->toArray())
    ->type
    ->toBe('boolean')
    ->group('filters');

it('should set a filter format')
    ->expect(fn () => Filter::make('company')->format('type-ahead')->toArray())
    ->format
    ->toBe('type-ahead')
    ->group('filters');

it('should set options from model filter attribute')
    ->expect(fn () => Filter::make('type')->fromAttribute()->build($this->property, null)->toArray())
    ->toHaveKey('options')
    ->options
    ->toHaveCount(9)
    ->group('filters');

it('should get options from function call')
    ->expect(fn () => Filter::make('type')->fromAttribute()->build($this->property, null)->toOptions())
    ->toHaveCount(9)
    ->group('filters');

it('should have a filter source')
    ->expect(fn () => Filter::make('types')->fromAttribute()->toArray())
    ->source
    ->toBe(Filter::SOURCE_ATTRIBUTE)
    ->group('filters');

it('should have a filter source meta based on name')
    ->expect(fn () => Filter::make('types')->fromAttribute()->toArray())
    ->sourceMeta
    ->toBe('types')
    ->group('filters');

it('should have a filter source meta from input')
    ->expect(fn () => Filter::make('types')->fromAttribute('type')->toArray())
    ->sourceMeta
    ->toBe('type')
    ->group('filters');

it('should have a key')
    ->expect(fn () => Filter::make('type')->toArray())
    ->key
    ->toBe('type')
    ->group('filters');

it('should have meta')
    ->expect(fn () => Filter::make('type')->meta(['column' => 'type'])->toArray())
    ->meta
    ->toBe(['column' => 'type'])
    ->group('filters');

it('should have a uuid')
    ->expect(fn () => Filter::make('type')->meta(['column' => 'type'])->toArray())
    ->uuid
    ->toBeString()
    ->toHaveLength(36)
    ->group('filters');

it('should have a default value')
    ->expect(fn () => Filter::make('type')->default('small')->toArray())
    ->toMatchArray(['default' => 'small', 'value' => 'small', 'is_default' => true])
    ->group('filters');

it('should set options from model filter function', function () {
    $this->companies = Company::factory()->count(10)->hasProperties(5)->create();
    $this->companies = Company::with('properties')->whereIn('id', $this->companies->pluck('id')->all())->get();
    $query = Property::select('id', 'name', 'company_id', 'options')->with('company');

    $filter = Filter::make('company')->fromFunction()->option('id', 'name')->build($this->property, $query)->toArray();
    expect($filter['options'])->toHaveCount(10);
})->group('filters');

it('should throw error when building without a source set', function () {
    $query = Property::select('id', 'name', 'company_id', 'options')->with('company');

    expect(fn () => Filter::make('company')->build($this->property, $query)->toArray())
        ->toThrow('Cannot build filter without source set');
})->group('filters');

it('should set options from query column', function () {
    $this->companies = Company::factory()->count(10)->hasProperties(5)->create();
    $this->companies = Company::with('properties')->whereIn('id', $this->companies->pluck('id')->all())->get();
    $query = Property::select('id', 'name', 'company_id', 'options')->with('company');

    $filter = Filter::make('type')->fromColumn()->build($this->property, $query)->toArray();
    expect(count($filter['options']))->toBeGreaterThan(0);
    expect($filter['options'])->each->toHaveKeys(['value', 'label']);
    expect($filter)->toHaveKey('optionValue', 'value')->toHaveKey('optionLabel', 'label');
})->group('filters');

it('should throw error building from an invalid attribute', function () {
    $query = Property::select('id', 'name', 'company_id', 'options')->with('company');

    expect(fn () => Filter::make('company')->fromAttribute('invalid')->build($this->property, $query)->toArray())
        ->toThrow('Attribute missing for filter');
})->group('filters');

it('should throw error building from an invalid function', function () {
    $query = Property::select('id', 'name', 'company_id', 'options')->with('company');

    expect(fn () => Filter::make('company')->fromFunction('invalid')->build($this->property, $query)->toArray())
        ->toThrow('Could not find filter function for filter');
})->group('filters');

it('should get an item from a callable function', function () {
    $query = Property::select('id', 'name', 'company_id', 'options')->with('company');
    $filter = Filter::make('type')->fromAttribute()->itemValue(fn ($value) => ['fieldLabel' => 'Test', 'fieldValue' => $value])->value(200);

    expect($filter->setItem()->build($this->property, $query)->toArray()['item'])->toMatchArray(['fieldLabel' => 'Test', 'fieldValue' => 200]);
})->group('filters');

it('should get a filter item for a value')
    ->expect($filter = Filter::make('type')->fromAttribute()->itemValue(fn ($value) => ['fieldLabel' => 'Test', 'fieldValue' => $value])->getItem(200))
    ->toMatchArray(['fieldLabel' => 'Test', 'fieldValue' => 200])
    ->group('filters');
