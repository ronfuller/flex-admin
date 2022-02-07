<?php


use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;
use Psi\FlexAdmin\Tests\Models\User;

beforeEach(function () {
    $this->properties = Property::factory()->count(5)
        ->forCompany()
        ->state(new Sequence(
            ['created_at' => now()->subDays(5), 'name' => 'Everest', 'options' => ['color' => 'blue',], 'status' => 'success', 'type' => 'townhome'],
            ['created_at' => now()->subDays(3), 'name' => 'Cascade', 'options' => ['color' => 'green',], 'status' => 'fail', 'type' => 'apartment'],
            ['created_at' => now()->subDays(10), 'name' => 'Denali', 'options' => ['color' => 'violet',], 'status' => 'fail', 'type' => 'home'],
            ['created_at' => now()->subDays(13), 'name' => 'Cameroon', 'options' => ['color' => 'blue green',], 'status' => 'fail', 'type' => 'duplex'],
            ['created_at' => now()->subDays(35), 'name' => 'Rainier', 'options' => ['color' => 'light blue',], 'status' => 'fail', 'type' => 'commercial'],
        ))
        ->create();
    $this->user = User::factory()->create(
        [
            'permissions' => ['properties.view-any', 'properties.view', 'properties.edit', 'properties.delete', 'properties.create'],
        ]
    );
    actingAs($this->user);
    Route::resource('properties', TestController::class);
});

it('should create a collection for a property')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->flexModel)
    ->toBeInstanceOf(Property::class);

it('should create a collection for a property with short syntax')
    ->expect(fn () => Flex::forIndex(Property::class)->flexModel)
    ->toBeInstanceOf(Property::class);

it('should create a collection for a property with short syntax for detail')
    ->expect(fn () => Flex::forDetail(Property::class)->flexModel)
    ->toBeInstanceOf(Property::class);

it('should create a collection for a property with short syntax for edit')
    ->expect(fn () => Flex::forEdit(Property::class)->flexModel)
    ->toBeInstanceOf(Property::class);

it('should create a collection for a property with short syntax for create')
    ->expect(fn () => Flex::forCreate(Property::class)->flexModel)
    ->toBeInstanceOf(Property::class);

it('should throw error on missing resource', function () {
    expect(fn () => Flex::forIndex("Psi\LaravelFlexAdmin\Tests\Models\NotThere")->flexModel)
        ->toThrow('Class "Psi\LaravelFlexAdmin\Tests\Models\NotThere" not found');
});

it('should throw error on invalid context', function () {
    expect(fn () => Flex::for(Property::class, 'invalid-context')->flexModel)
        ->toThrow("Unknown context");
});

it('should create a collects property')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->collects)
    ->not->toBeNull();

it('should query a resource')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest())->count())
    ->toBe(5);

it('should output to an array')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->query(createRequest())->toArray(createRequest()))
    ->toBeArray();

it('should search on property name')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['search' => 'everest']))
        ->count())
    ->toBe(1)
    ->group('search');

it('should search on full color match with JSON column')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['search' => 'blue']))
        ->count())
    ->toBe(3)
    ->group('search');

it('should search on type partial match')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['search' => 'hom']))
        ->count())
    ->toBe(1)
    ->group('search');

it('should search on type exact match')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['search' => 'success']))
        ->count())
    ->toBe(1)
    ->group('search');

it('should constrain on property Id')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['propertyId' => $this->properties->first()->id]))
        ->count())
    ->toBe(1)
    ->group('constraint');

it('should constrain on color blue')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['color' => 'blue']))
        ->count())
    ->toBe(1)
    ->group('constraint');

it('should apply a constraint')
    ->expect(
        fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)
            ->withConstraints(['type' => 'home'])
            ->withoutFilters()
            ->query(createRequest())
    )->count()
    ->toBe(1)
    ->group('constraint');


it('should have an ordered query name desc')
    ->expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest())
        ->resource->first())
    ->name
    ->toBe('Rainier')
    ->group('order');

it('should have an ordered query type asc')
    ->expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['sort' => 'type', 'descending' => false]))
        ->resource->first())
    ->type
    ->toBe('apartment')
    ->group('order');

it('should throw an error when there is no default sort order', function () {
    expect(fn () => Flex::for(Company::class, FIELD::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest()))
        ->toThrow("Error. Default sort is required for resource.");
})->group('order');

it('should throw an error when there is an invalid sort direction', function () {
    config(['flex-admin.sort.direction.flag' => null]);
    expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['sort' => 'type', 'descending' => 'abc'])))
        ->toThrow("Invalid sort direction");
})->group('order');


it('should have an ordered query type desc')
    ->expect(fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)
        ->withoutFilters()
        ->query(createRequest(['sort' => 'type', 'descending' => true]))
        ->resource->first())
    ->type
    ->toBe('townhome')
    ->group('order');

it('should paginate the query')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::for(Property::class, FIELD::CONTEXT_INDEX)
            ->withoutFilters()
            ->query(createRequest(['type' => $type]))
            ->resource->count();
    })
    ->toBe(15)
    ->group('paginate');

it('should not paginate the query')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::for(Property::class, FIELD::CONTEXT_INDEX)
            ->withoutPagination()
            ->withoutFilters()
            ->query(createRequest(['type' => $type]))
            ->resource->count();
    })->toBe(100)
    ->group('paginate');

it('should paginate from request scope')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::for(Property::class, FIELD::CONTEXT_INDEX)
            ->withoutFilters()
            ->query(createRequest(['type' => $type, 'perPage' => 20]))
            ->resource->count();
    })->toBe(20)
    ->group('paginate');

it('should create pagination meta')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::for(Property::class, FIELD::CONTEXT_INDEX)
            ->withoutFilters()
            ->toArray(createRequest(['type' => $type, 'perPage' => 20]));
    })->toHaveKey('pagination')
    ->group('paginate');

it('should output to an array with rows data')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutFilters()->query(createRequest())->toArray(createRequest()))
    ->data
    ->toHaveCount(5)
    ->each->toHaveKeys(['fields', 'values', 'actions']);

it('should have default actions for rows data')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)->withoutFilters()->query(createRequest())->toArray(createRequest()))
    ->data
    ->toHaveCount(5)
    ->each->toHaveKey('actions.0.slug', 'view')
    ->each->toHaveKey('actions.1.slug', 'edit')
    ->each->toHaveKey('actions.2.slug', 'delete');

it('should create rows for a large data set', function () {
    $count = 100;
    $properties = Property::factory()->count($count)->forCompany()->create();
    ray()->measure();
    $data = Flex::forIndex(Property::class)->withoutFilters()->toArray(createRequest(['perPage' => $count]));
    ray()->measure();
    expect($data['data'])->toHaveCount($count);
});

it('should execute query in to array if not ran')
    ->expect(fn () => Flex::forIndex(Property::class)->withoutFilters()->toArray(createRequest()))
    ->data
    ->toHaveCount(5)
    ->each->toHaveKeys(['fields', 'values', 'actions']);

it('should create filter options for query')
    ->expect(fn () => Flex::forIndex(Property::class)->withoutDefaultFilters()->queryFilters(createRequest()))
    ->flexFilters
    ->each(fn ($filter) => $filter->options->not->toBeEmpty());


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
