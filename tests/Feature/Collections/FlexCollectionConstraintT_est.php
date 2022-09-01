<?php

use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should constrain on property Id')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest(['propertyId' => $this->properties->first()->id]))
        ->count())
    ->toBe(1)
    ->group('collections', 'constraint');

it('should constrain on color blue')
    ->expect(fn () => Flex::for(Property::class, Field::CONTEXT_INDEX)
        ->withoutFilters()
        ->withoutCache()
        ->query(createRequest(['color' => 'blue']))
        ->count())
    ->toBe(1)
    ->group('collections', 'constraint');

it('should apply a constraint')
    ->expect(
        fn () => Flex::for(Property::class, FIELD::CONTEXT_INDEX)
            ->withConstraints(['type' => 'home'])
            ->withoutFilters()
            ->withoutCache()
            ->query(createRequest())
    )->count()
    ->toBe(1)
    ->group('collections', 'constraint');
