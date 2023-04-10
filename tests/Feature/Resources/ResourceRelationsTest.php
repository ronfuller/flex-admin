<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;

it('should have relations')
->expect(fn () => (new PropertyResource($this->property->load('company')))
    ->withContext(Field::CONTEXT_DETAIL)
    ->toArray(createRequest()))
    ->toHaveKey('relations')
    ->relations
    ->toHaveCount(2)
    ->group('resources', 'relation');

it('should have an empty relations when without')
    ->expect(fn () => (new PropertyResource($this->property))->withoutRelations()->withContext(Field::CONTEXT_DETAIL)->toArray(createRequest()))
    ->toHaveKey('relations')
    ->relations
    ->toHaveCount(0)
    ->group('resources', 'relation');

it('should filter relations')
    ->expect(fn () => (new PropertyResource($this->property->load('company')))
        ->withContext(Field::CONTEXT_DETAIL)
        ->onlyRelations(['company'])
        ->toArray(createRequest()))
    ->toHaveKey('relations')
    ->relations
    ->toHaveCount(1)
    ->toHaveKey('company')
    ->group('resources', 'relation');
