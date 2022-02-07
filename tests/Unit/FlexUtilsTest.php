<?php

use Psi\FlexAdmin\Lib\FlexUtils;
use Psi\FlexAdmin\Tests\Models\ApplicationGroup;
use Psi\FlexAdmin\Tests\Models\Property;

beforeEach(function () {
});

it('should create a title')
    ->expect(fn () => (new FlexUtils(new Property()))->title('edit'))
    ->toBe('Edit Property');

it('should create a title for camel case model')
    ->expect(fn () => (new FlexUtils(new ApplicationGroup()))->title('edit'))
    ->toBe('Edit Application Group');

it('should create a permission')
    ->expect(fn () => (new FlexUtils(new Property()))->permission('edit'))
    ->toBe('properties.edit');

it('should create a permission for camel case model')
    ->expect(fn () => (new FlexUtils(new ApplicationGroup()))->permission('edit'))
    ->toBe('application-groups.edit');

it('should create a view route name')
    ->expect(fn () => (new FlexUtils(new Property()))->route('view', new Property()))
    ->toBe(['properties.show', 'get', ['property' => null]]);

it('should create a create route name')
    ->expect(fn () => (new FlexUtils(new Property()))->route('create', new Property()))
    ->toBe(['properties.create', 'get', []]);

it('should create a delete route name')
    ->expect(fn () => (new FlexUtils(new Property()))->route('delete', new Property()))
    ->toBe(['properties.destroy', 'delete', ['property' => null]]);

it('should create an edit route name')
    ->expect(fn () => (new FlexUtils(new Property()))->route('edit', new Property()))
    ->toBe(['properties.edit', 'get', ['property' => null]]);

it('should create an edit route name for camel case model')
    ->expect(fn () => (new FlexUtils(new ApplicationGroup()))->route('edit', new ApplicationGroup()))
    ->toBe(['application-groups.edit', 'get', ['application_group' => null]]);
