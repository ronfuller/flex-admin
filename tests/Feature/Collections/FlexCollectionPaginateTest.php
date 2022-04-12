<?php

use Illuminate\Support\Str;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should paginate the query')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::for(Property::class, Field::CONTEXT_INDEX)
            ->withoutFilters()
            ->withoutCache()
            ->query(createRequest(['type' => $type]))
            ->resource->count();
    })
    ->toBe(15)
    ->group('collections', 'paginate');

it('should not paginate the query')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::for(Property::class, Field::CONTEXT_INDEX)
            ->withoutPagination()
            ->withoutFilters()
            ->withoutCache()
            ->query(createRequest(['type' => $type]))
            ->resource->count();
    })->toBe(100)
    ->group('collections', 'paginate');

it('should paginate from request scope')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::for(Property::class, Field::CONTEXT_INDEX)
            ->withoutFilters()
            ->withoutCache()
            ->query(createRequest(['type' => $type, 'perPage' => 20]))
            ->resource->count();
    })->toBe(20)
    ->group('collections', 'paginate');

it('should create pagination meta')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::for(Property::class, Field::CONTEXT_INDEX)
            ->withoutFilters()
            ->withoutCache()
            ->toArray(createRequest(['type' => $type, 'perPage' => 20]));
    })->toHaveKey('pagination')
    ->group('collections', 'paginate');
