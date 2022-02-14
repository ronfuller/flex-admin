<?php


use function Pest\Laravel\getJson;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;
use Psi\FlexAdmin\Tests\Models\Property;

it('should create a collection for a property')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->flexModel)
    ->toBeInstanceOf(Property::class)
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
        ->toThrow('Class "Psi\LaravelFlexAdmin\Tests\Models\NotThere" not found');
})
    ->group('collections');

it('should throw error on invalid context', function () {
    expect(fn () => Flex::for(Property::class, 'invalid-context')->flexModel)
        ->toThrow("Unknown context");
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
    ->data
    ->toHaveCount(5)
    ->each->toHaveKeys(['fields', 'values', 'actions']);

it('should have default actions for rows data')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->toArray(createRequest()))
    ->data
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
    expect($data['data'])->toHaveCount($count);
})
    ->group('collections');


it('should cache the column meta', function () {
    $count = 100;
    $properties = Property::factory()->count($count)->forCompany()->create();
    $response = getJson("/properties?count={$count}")
        ->assertOk();
    expect(collect(array_keys(session()->all()))->contains(fn ($key) => str($key)->contains("property-index")))->toBeTrue();
})
    ->group('collections');


    /*
        Full Signature

        Flex::for(Class,Context)

           ->authorizeScope(string '' )
           ->withoutAuthorize()

           ->withConstraints()

            ->orderScope()

            ->filterScope()

            ->searchScope()

            ->indexScope()

            ->detailScope()

            ->editScope()

            ->createScope()

            ->withScopes(['',''])

            ->withoutGlobalScopes([''])

            ->withoutPagination()

            ->withoutRelations()
            ->withoutRelation(Related)

            ->filters()

            ->wrapper()

            ->query()

            ->count()

            ->transform()

            ->toArray()

            ->toResponse()


*/
