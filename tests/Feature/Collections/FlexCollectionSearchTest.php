<?php

use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should search on property name')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest(['search' => 'everest']))
        ->count())
    ->toBe(1)
    ->group('search', 'collections');

it('should search on company name')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->searchScope('search')
        ->query(createRequest(['search' => 'columbia']))
        ->count())
    ->toBe(5)
    ->group('search', 'collections');

it('should show empty results for search on invalid company name')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->searchScope('search')
        ->query(createRequest(['search' => 'invalid']))
        ->count())
    ->toBe(0)
    ->group('search', 'collections');

it('should search on full color match with JSON column')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest(['search' => 'blue']))
        ->count())
    ->toBe(3)
    ->group('search', 'collections');

it('should search on type partial match')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest(['search' => 'hom']))
        ->count())
    ->toBe(1)
    ->group('search', 'collections');

it('should search on type exact match')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest(['search' => 'success']))
        ->count())
    ->toBe(1)
    ->group('search', 'collections');
