<?php

use Illuminate\Support\Str;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Tests\Models\Property;

it('should paginate the query')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::forIndex(Property::class)
            ->withoutFilters()
            ->toArray(createRequest(['type' => $type]));
    })
    ->rows
    ->toHaveCount(15)
    ->group('collections', 'paginate');

it('should not paginate the query')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::forIndex(Property::class)
            ->withoutPagination()
            ->withoutDefaultFilters()
            ->toArray(createRequest(['filter' => "type:{$type}"]));
    })
    ->rows
    ->toHaveCount(100)
    ->group('collections', 'paginate');

it('should paginate from request scope')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::forIndex(Property::class)
            ->withoutFilters()
            ->toArray(createRequest(['type' => $type, 'perPage' => 20]));
    })
    ->rows
    ->toHaveCount(20)
    ->group('collections', 'paginate');

it('should create pagination meta')
    ->expect(function () {
        $type = Str::random(10);
        Property::factory()->count(100)->forCompany()->create(['type' => $type]);

        return Flex::forIndex(Property::class)
            ->withoutFilters()
            ->toArray(createRequest(['type' => $type, 'perPage' => 20]));
    })
    ->toHaveKey('pagination')
    ->pagination->toHaveKeys(['sort', 'descending', 'page', 'rowsPerPage', 'rowsNumber', 'currentPage', 'from', 'to', 'total', 'nextUrl', 'previousUrl', 'next', 'previous'])
    ->group('collections', 'paginate');
