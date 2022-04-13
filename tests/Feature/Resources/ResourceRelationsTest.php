<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;

it('should have relations')
    ->expect(fn () => (new PropertyResource($this->property))->withContext(Field::CONTEXT_DETAIL)->toArray(createRequest()))
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
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_DETAIL)
        ->onlyRelations(['company'])
        ->toArray(createRequest()))
    ->toHaveKey('relations')
    ->relations
    ->toHaveCount(1)
    ->toHaveKey('company')
    ->group('resources', 'relation');

it('should filter relations for multiple relations')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_DETAIL)
        ->onlyRelations(['company', 'units'])
        ->toArray(createRequest()))
    ->toHaveKey('relations')
    ->relations
    ->toHaveCount(2)
    ->toHaveKeys(['company', 'units'])
    ->group('resources', 'relation');
