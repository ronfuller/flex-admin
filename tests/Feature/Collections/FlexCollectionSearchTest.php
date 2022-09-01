<?php

use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should search on property name with a match')
    ->expect(fn () => Flex::forIndex(Property::class)
        ->toArray(createRequest(['search' => 'everest'])))
    ->rows
    ->toHaveCount(1)
    ->group('search', 'collections');

it('should search on property name without a match')
    ->expect(fn () => Flex::forIndex(Property::class)
        ->toArray(createRequest(['search' => 'invalid-property'])))
    ->rows
    ->toBeEmpty()
    ->group('search', 'collections');

it('should search on company name')
    ->expect(fn () => Flex::forIndex(Property::class, Field::CONTEXT_INDEX)
        ->toArray(createRequest(['search' => 'columbia'])))
    ->rows
    ->toHaveCount(5)
    ->group('search', 'collections');

it('should show empty results for search on invalid company name')
    ->expect(fn () => Flex::forIndex(Property::class, Field::CONTEXT_INDEX)
        ->toArray(createRequest(['search' => 'invalid'])))
    ->rows
    ->toBeEmpty()
    ->group('search', 'collections');

it('should search on full color match with JSON column')
    ->expect(fn () => Flex::forIndex(Property::class, Field::CONTEXT_INDEX)
        ->toArray(createRequest(['search' => 'blue'])))
    ->rows
    ->toHaveCount(3)
    ->group('search', 'collections');

it('should search on type partial match')
    ->expect(fn () => Flex::forIndex(Property::class, Field::CONTEXT_INDEX)
        ->toArray(createRequest(['search' => 'hom'])))
    ->rows
    ->toHaveCount(2)
    ->group('search', 'collections');

it('should search on type exact match')
    ->expect(fn () => Flex::forIndex(Property::class, Field::CONTEXT_INDEX)
        ->toArray(createRequest(['search' => 'success'])))
    ->rows
    ->toHaveCount(1)
    ->group('search', 'collections');
