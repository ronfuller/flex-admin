<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;
use Psi\FlexAdmin\Tests\Models\Property;

it('should return default pagination')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->perPage
    ->toBe(15)
    ->group('resources', 'pagination');

it('should set pagination')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->setPerPage(20)
        ->toMeta(new Property()))
    ->perPage
    ->toBe(20)
    ->group('resources', 'pagination');

it('should get default per page options')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->toMeta(new Property()))
    ->perPageOptions
    ->toMatchArray([5, 15, 25, 50, 75, 100])
    ->group('resources', 'pagination');

it('should set default per page options')
    ->expect(fn () => (new PropertyResource(null))
        ->withContext(Field::CONTEXT_INDEX)
        ->setPerPageOptions([25, 50])
        ->toMeta(new Property()))
    ->perPageOptions
    ->toMatchArray([25, 50])
    ->group('resources', 'pagination');
