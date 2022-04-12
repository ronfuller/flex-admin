<?php

use Illuminate\Support\Facades\Route;
use function Pest\Laravel\getJson;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;

beforeEach(function () {
    Route::resource('companies', TestController::class);
});

it('should create a collection for a property')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->flexModel)
    ->toBeInstanceOf(Property::class)
    ->group('collections');

it('should create an Inertia Page')
    ->expect(fn () => Flex::forIndex(Property::class)->page('Admin')->page)
    ->toBe('Admin')
    ->group('collections');

it('should create a collection for a property with short syntax')
    ->expect(fn () => Flex::forIndex(Property::class)->flexModel)
    ->toBeInstanceOf(Property::class)
    ->group('collections');

it('should create a collection for a property with short syntax for detail')
    ->expect(fn () => Flex::forDetail(Property::class)->flexModel)
    ->toBeInstanceOf(Property::class)
    ->group('collections');

it('should create a collection for a property with short syntax for edit')
    ->expect(fn () => Flex::forEdit(Property::class)->flexModel)
    ->toBeInstanceOf(Property::class)
    ->group('collections');

it('should create a collection for a property with short syntax for create')
    ->expect(fn () => Flex::forCreate(Property::class)->flexModel)
    ->toBeInstanceOf(Property::class)
    ->group('collections');

it('should throw error on missing resource', function () {
    expect(fn () => Flex::forIndex("Psi\LaravelFlexAdmin\Tests\Models\NotThere")->flexModel)
        ->toThrow('not found');
})
    ->group('collections');

it('should throw error on invalid context', function () {
    expect(fn () => Flex::for(Property::class, 'invalid-context')->flexModel)
        ->toThrow('Unknown context');
})
    ->group('collections');

it('should create a collects property')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->collects)
    ->toBe(PropertyResource::class)
    ->group('collections');

it('should output to an array with rows data')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest())
        ->toArray(createRequest()))
    ->rows
    ->toHaveCount(5)
    ->each->toHaveKeys(['uuid', 'actions'])
    ->group('collections');

it('should have default actions for rows data')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->toArray(createRequest()))
    ->rows
    ->toHaveCount(5)
    ->each->toHaveKey('actions.0.slug', 'view')
    ->each->toHaveKey('actions.1.slug', 'edit')
    ->each->toHaveKey('actions.2.slug', 'delete')
    ->group('collections');

it('should create rows for a large data set', function () {
    $count = 100;
    $properties = Property::factory()->count($count)->forCompany()->create();
    ray()->measure();
    $data = Flex::forIndex(Property::class)->withoutFilters()->toArray(createRequest(['perPage' => $count]));
    ray()->measure();
    expect($data['rows'])->toHaveCount($count);
})
    ->group('collections');

it('should cache the column meta', function () {
    config(['flex-admin.cache_enabled' => true]);
    $count = 100;
    $properties = Property::factory()->count($count)->forCompany()->create();
    $response = getJson("/properties?count={$count}")
        ->assertOk();
    expect(collect(array_keys(session()->all()))->contains(fn ($key) => str($key)->contains('property-index')))->toBeTrue();
})
    ->group('collections');

it('should have a detail resource with a belongsTo relationship', function () {
    $property = Property::factory()->forCompany()->create();

    $result = Flex::forDetail(Property::class)
        ->byId($property->id)
        ->toArray(createRequest());
    expect($result)->ray()->toHaveKey('data');
    expect($result)->data->toHaveKeys(['actions', 'values', 'panels', 'relations']);
    expect(data_get($result, 'data.actions'))->toHaveCount(3);
    expect(data_get($result, 'data.values'))->toHaveCount(3);
    expect(data_get($result, 'data.relations'))->toHaveKey('company');
})->group('collections');

it('should have a detail resource with a hasMany relationship', function () {
    $properties = Property::factory(5)->forCompany()->create();

    $result = Flex::forDetail(Company::class)
        ->byId($properties->first()->company_id)
        ->toArray(createRequest());
    expect($result)->ray()->toHaveKey('data');
})->group('collections');

    /*
        Full Signature

        Flex::for(Class,Context)
            ->byId()    // detail, edit
            ->where()
            ->withoutCache()

           ->authorizeScope(string '' )
           ->orderScope(string '')
           ->filterScope()
           ->searchScope()

           ->indexScope()       // replacment scope for INDEX context
           ->detailScope()      // replacment scope for DETAIL context
           ->editScope()        // replacment scope for EDIT context
           ->createScope()      // replacemnent scope for CREATE context

           ->withScope()  // add additinal scopes to query

           ->withoutAuthorize()
           ->withoutConstraints()

           ->withScopes(['',''])
           ->withoutGlobalScopes([''])

            ->withoutPagination()

            ->withoutRelations()
            ->withoutRelation(Related)

            ->withoutFilters()
            ->withoutDefaultFilters()
            ->withoutDeferredFilters()

            ->wrapper(string $element)

            ->query()
            ->count()

            ->transform( callable )

            ->toArray()
            ->toResponse()

*/
