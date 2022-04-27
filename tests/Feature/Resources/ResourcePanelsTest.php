<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Resources\PropertyResource;

it('should have a default details panel')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_DETAIL)
        ->withoutRelations()
        ->toArray(createRequest()))
    ->toHaveKey('panels')
    ->group('resources', 'panel');

it('should add fields to default details panel')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withoutRelations()
        ->withContext(Field::CONTEXT_DETAIL)
        ->toArray(createRequest())['panels'][0])
    ->fields
    ->toHaveCount(9)
    ->each
    ->toHaveKeys(['attributes', 'value'])
    ->group('resources', 'panel');

it('should have empty panels without panels')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_DETAIL)
        ->withoutRelations()
        ->withoutPanels()
        ->toArray(createRequest()))
    ->toHaveKey('panels', [])
    ->group('resources', 'panel');

it('should hide panels without fields')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withContext(Field::CONTEXT_DETAIL)
        ->withoutRelations()
        ->toArray(createRequest()))
    ->panels
    ->toHaveCount(1)
    ->group('resources', 'panel');

it('should have not have panels in index context')
    ->expect(fn () => (new PropertyResource($this->property))
        ->withoutRelations()
        ->withContext(Field::CONTEXT_INDEX)
        ->toArray(createRequest()))
    ->not
    ->toHaveKey('panels')
    ->group('resources', 'panel');

it('should throw an error when setting a key to an invalid panel', function () {
    $field = Field::make(null, 'id')->panel('invalid');

    expect(fn () => (new PropertyResource($this->property))
        ->addField($field)
        ->withContext(Field::CONTEXT_DETAIL)
        ->withoutRelations()
        ->toArray(createRequest()))
        ->toThrow('Could not find panel for key');
})
    ->group('resources', 'panel');
