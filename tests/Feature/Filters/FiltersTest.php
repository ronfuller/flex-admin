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
    ->expect(Filter::make('company'))
    ->not()
    ->toBeNull();

it('should have a filter name')
    ->expect(Filter::make('company')->toArray())
    ->name
    ->toBe('company');

it('should have a default type of filter')
    ->expect(Filter::make('company')->toArray()['type'])
    ->toBe('select');

it('should have a default label')
    ->expect(Filter::make('company')->toArray())
    ->label
    ->toBe('Companies');

it('should have a default label from snake case')
    ->expect(Filter::make('company_user')->toArray())
    ->label
    ->toBe('Company Users');

it('should have a default label from kebab case')
    ->expect(Filter::make('company-user')->toArray())
    ->label
    ->toBe('Company Users');

it('should have an icon')
    ->expect(Filter::make('company')->icon('mdi-domain')->toArray())
    ->prependIcon
    ->toBe('mdi-domain');

it('should have an option configuration')
    ->expect(Filter::make('company')->option('name', 'id')->toArray())
    ->toHaveKey('optionValue', 'id')
    ->toHaveKey('optionLabel', 'name');

it('should append attributes')
    ->expect(Filter::make('company')->attributes(
        [
            'dense' => true,
            'optionsDense' => true,
        ]
    )->toArray())
    ->toHaveKeys(['label', 'name', 'dense', 'optionsDense']);

it('should throw exception appending reserved keys', function () {
    expect(fn () => Filter::make('company')->attributes(
        [
            'name' => 'test',
            'label' => 'test label',
        ]
    ))
        ->toThrow("Cannot append attributes");
});

it('should set filter type to boolean')
    ->expect(Filter::make('company')->boolean()->toArray())
    ->type
    ->toBe('boolean');

it('should set a filter format')
    ->expect(Filter::make('company')->format('type-ahead')->toArray())
    ->format
    ->toBe('type-ahead');

it('should set options from model filter attribute')
    ->expect(fn () => Filter::make('type')->fromAttribute()->build($this->property, null)->toArray())
    ->toHaveKey('options')
    ->options
    ->toHaveCount(9);

it('should have a filter source')
    ->expect(fn () => Filter::make('types')->fromAttribute()->toArray())
    ->source
    ->toBe(Filter::SOURCE_ATTRIBUTE);

it('should have a filter source meta based on name')
    ->expect(fn () => Filter::make('types')->fromAttribute()->toArray())
    ->sourceMeta
    ->toBe('types');

it('should have a filter source meta from input')
    ->expect(fn () => Filter::make('types')->fromAttribute('type')->toArray())
    ->sourceMeta
    ->toBe('type');

it('should have a key')
    ->expect(fn () => Filter::make('type')->toArray())
    ->key
    ->toBe('type');

it('should have meta')
    ->expect(fn () => Filter::make('type')->meta(['column' => 'type'])->toArray())
    ->meta
    ->toBe(['column' => 'type']);

it('should have a uuid')
    ->expect(fn () => Filter::make('type')->meta(['column' => 'type'])->toArray())
    ->uuid
    ->toBeString()
    ->toHaveLength(36);

it('should have a default value')
    ->expect(fn () => Filter::make('type')->default('small')->toArray())
    ->toMatchArray(['default' => 'small', 'value' => 'small', 'is_default' => true]);

it('should set options from model filter function', function () {
    $this->companies = Company::factory()->count(10)->hasProperties(5)->create();
    $this->companies = Company::with('properties')->whereIn('id', $this->companies->pluck('id')->all())->get();
    $query = Property::select('id', 'name', 'company_id', 'options')->with('company');

    $filter = Filter::make('company')->fromFunction()->option('id', 'name')->build($this->property, $query)->toArray();
    expect($filter['options'])->toHaveCount(10);
});

it('should set options from query column', function () {
    $this->companies = Company::factory()->count(10)->hasProperties(5)->create();
    $this->companies = Company::with('properties')->whereIn('id', $this->companies->pluck('id')->all())->get();
    $query = Property::select('id', 'name', 'company_id', 'options')->with('company');

    $filter = Filter::make('type')->fromColumn()->build($this->property, $query)->toArray();
    expect(count($filter['options']))->toBeGreaterThan(0);
    expect($filter['options'])->each->toHaveKeys(['value', 'label']);
    expect($filter)->toHaveKey('optionValue', 'value')->toHaveKey('optionLabel', 'label');
});

it('should get an item from a callable function', function () {
    $query = Property::select('id', 'name', 'company_id', 'options')->with('company');
    $filter = Filter::make('type')->fromAttribute()->itemValue(fn ($value) => ['fieldLabel' => 'Test', 'fieldValue' => $value])->value(200);

    expect($filter->setItem()->build($this->property, $query)->toArray()['item'])->toMatchArray(['fieldLabel' => 'Test', 'fieldValue' => 200]);
});
