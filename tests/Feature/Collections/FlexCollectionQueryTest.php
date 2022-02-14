<?php

use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should query a resource')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest())->count())
    ->toBe(5)
    ->group('collections', 'query');

it('should output to an array')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutCache()
        ->query(createRequest())->toArray(createRequest()))
    ->toBeArray()
    ->group('collections', 'query');

it('should execute query in to array if not ran')
    ->expect(fn () => Flex::forIndex(Property::class)
        ->withoutFilters()
        ->withoutCache()
        ->toArray(createRequest()))
    ->data
    ->toHaveCount(5)
    ->each->toHaveKeys(['fields', 'values', 'actions'])
    ->group('collections', 'query');
