<?php

use Psi\FlexAdmin\Lib\FlexInspect;
use Psi\FlexAdmin\Tests\Models\ApplicationGroup;
use Psi\FlexAdmin\Tests\Models\Property;

beforeEach(function () {
});

it('should inspect model meta')
    ->expect(fn () => (new FlexInspect(new Property()))->meta)
    ->toMatchArray([
        'name' => 'property',
        'pluralName' => 'properties',
        'table' => 'properties',
        'primaryKey' => 'id',
        'foreignKey' => 'property_id',
    ])
    ->filterFunctions
    ->toHaveCount(1)
    ->filterAttributes
    ->toHaveCount(3)
    ->columns
    ->toHaveCount(11);

it('should inspect model meta for Pascal Case model name')
    ->expect(fn () => (new FlexInspect(new ApplicationGroup()))->meta)
    ->toMatchArray([
        'name' => 'application_group',
        'pluralName' => 'application-groups',
        'table' => 'application_groups',
        'primaryKey' => 'id',
        'foreignKey' => 'application_group_id',
    ]);
