<?php

use Illuminate\Support\Facades\Route;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;

beforeEach(function () {
    Route::resource('companies', TestController::class);
});

it('should create flex instance for a property model class')
    ->expect(fn () => Flex::forIndex(Property::class))
    ->toBeInstanceOf(Flex::class)
    ->group('collections');

it('should find the resource for the property model')
    ->expect(fn () => Flex::forIndex(Property::class)->resource)
    ->toBeInstanceOf(PropertyResource::class)
    ->group('collections');

it('should create an Inertia Page')
    ->expect(fn () => Flex::forIndex(Property::class)->page('Admin')->page)
    ->toBe('Admin')

    ->group('collections');

it('should create a collection for a property with short syntax for detail')
    ->expect(fn () => Flex::forDetail(Property::class))
    ->toBeInstanceOf(Flex::class)

    ->group('collections');

it('should create a collection for a property with short syntax for edit')
    ->expect(fn () => Flex::forEdit(Property::class))
    ->toBeInstanceOf(Flex::class)

    ->group('collections');

it('should create a collection for a property with short syntax for create')
    ->expect(fn () => Flex::forCreate(Property::class))
    ->toBeInstanceOf(Flex::class)
    ->group('collections');

it('should throw error on missing resource', function () {
    expect(fn () => Flex::forIndex("Psi\LaravelFlexAdmin\Tests\Models\NotThere"))
        ->toThrow('not found');
})
    ->group('collections');

it('should output to an array with rows data', function () {
    expect(Flex::forIndex(Property::class)
        ->toArray(createRequest()))
        ->rows
        ->toHaveCount(5);
})
    ->group('collections');

it('should paginate output data', function () {
    Property::factory()->count(20)->forCompany()->create();
    expect(Flex::forIndex(Property::class)
        ->toArray(createRequest(['perPage' => 15])))
        ->rows
        ->toHaveCount(15);
})
    ->group('collections');

it('should not paginate output data', function () {
    Property::factory()->count(20)->forCompany()->create();
    expect(Flex::forIndex(Property::class)
        ->withoutPagination()
        ->toArray(createRequest(['perPage' => 5])))
        ->rows
        ->toHaveCount(25);
})
    ->group('collections');

it('should have default actions for rows data')
    ->expect(fn () => Flex::forIndex(Property::class)
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
    $data = Flex::forIndex(Property::class)->toArray(createRequest(['perPage' => $count]));
    ray()->measure();
    expect($data['rows'])->toHaveCount($count);
})
    ->group('collections');

// it('should have a detail resource with a belongsTo relationship', function () {
//     $property = Property::factory()->forCompany()->create();

//     $result = Flex::forDetail(Property::class)
//         ->byId($property->id)
//         ->toArray(createRequest());
//     expect($result)->toHaveKey('data');
//     expect($result)->data->toHaveKeys(['actions', 'values', 'panels', 'relations']);
//     expect(data_get($result, 'data.actions'))->toHaveCount(3);
//     expect(data_get($result, 'data.values'))->toHaveCount(3);
//     expect(data_get($result, 'data.relations'))->toHaveKey('company');
// })->group('collections');

// it('should have a detail resource without actions', function () {
//     $property = Property::factory()->forCompany()->create();

//     $result = Flex::forDetail(Property::class)
//         ->withoutActions()
//         ->byId($property->id)
//         ->toArray(createRequest());
//     expect($result)->data->actions->toBeEmpty();
// })->group('collections');

// it('should have a detail resource with a hasMany relationship', function () {
//     $properties = Property::factory(5)->forCompany()->create();

//     $result = Flex::forDetail(Company::class)
//         ->byId($properties->first()->company_id)
//         ->toArray(createRequest());
//     expect($result)->toHaveKey('data');
// })->group('collections');

// it('should have a detail resource without relations', function () {
//     $property = Property::factory()->forCompany()->create();

//     $result = Flex::forDetail(Property::class)
//         ->withoutRelations()
//         ->byId($property->id)
//         ->toArray(createRequest());
//     expect($result)->data->relations->toBeEmpty();
// })->group('collections');

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

       ->withoutDefaultActions()
       ->withoutActions()

       ->indexScope()       // replacement scope for INDEX context
       ->detailScope()      // replacment scope for DETAIL context
       ->editScope()        // replacment scope for EDIT context
       ->createScope()      // replacemnent scope for CREATE context

       ->withScope()  // add additinal scopes to query

       ->withoutAuthorize()
       ->withoutConstraints()

       ->withScopes(['',''])
       ->withoutGlobalScopes([''])

        ->withoutPagination()

        ->withRelations()
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
