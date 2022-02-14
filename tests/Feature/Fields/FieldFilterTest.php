<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should be filterable')
    ->expect(fn () => Field::make(null, 'id')->filterable()->attributes)
    ->toHaveKey('filterable', true)
    ->group('attributes', 'fields');

it('should have a constrained column')
    ->expect(fn () => Field::make(null, 'id')->constrainable()->model(new Property())->context(Field::CONTEXT_INDEX)->toColumn())
    ->toHaveKey('constrainable', true)
    ->group('filter', 'fields');

it('should have constraints')
    ->expect(fn () => Field::make(null, 'id')->constrainable()->model(new Property())->toColumn())
    ->toHaveKey('constrainable', true)
    ->group('filter', 'fields');
