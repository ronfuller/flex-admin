<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have a related model')
    ->expect(fn () => Field::make(null, 'companyName')
        ->on(Company::class)
        ->select('name')
        ->model(new Property())
        ->toMeta())
    ->toBeArray()
    ->select
    ->toBe('companies.name as companyName')
    ->column
    ->toBe('companies.name')
    ->sort
    ->toBe('companies.name')
    ->group('relations', 'fields');

it('should have a related model join')
    ->expect(fn () => Field::make(null, 'companyName')
        ->on(Company::class)
        ->select('name')
        ->model(new Property())
        ->toMeta())
    ->toBeArray()
    ->join
    ->toBe(['companies', 'companies.id', '=', 'properties.company_id'])
    ->group('relatioins', 'fields');
