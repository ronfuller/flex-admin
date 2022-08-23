<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should render model meta for the resource')
    ->expect(
        fn () => Field::make(null, 'id')
            ->modelMeta(new Property())
    )
    ->toHaveKeys(['class', 'name', 'pluralName', 'filterFunctions', 'filterAttributes', 'table', 'created_at', 'updated_at', 'tableSegment', 'columns', 'casts', 'relations', 'globalScopes', 'primaryKey', 'primaryKeyColumn', 'routeKey', 'foreignKey', 'perPage'])
    ->group('model', 'fields');
