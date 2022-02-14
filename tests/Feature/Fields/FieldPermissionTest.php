<?php

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have default permissions')
    ->expect(fn () => Field::make(null, 'id')->permissions)
    ->not->toBeNull()
    ->group('permissions', 'fields');

it('should have permission to index the resource')
    ->expect(fn () => Field::make(null, 'id')->withPermissions(Field::CONTEXT_INDEX, new Property())->model(new Property())->toColumn())
    ->toHaveKey('enabled', true)
    ->group('permissions', 'fields');

it('should not have permission to index the resource with specific permission')
    ->expect(fn () => Field::make(null, 'id')->indexPermission('properties.admin')->withPermissions(Field::CONTEXT_INDEX, new Property())->model(new Property())->toColumn())
    ->toHaveKey('enabled', false)
    ->group('permissions', 'fields');

it('should not have permission to edit the resource')
    ->expect(function () {
        $this->user->revokePermissionTo('properties.edit');

        return Field::make(null, 'id')->withPermissions(Field::CONTEXT_EDIT, new Property())->model(new Property())->toColumn();
    })
    ->toHaveKey('enabled', false)
    ->group('permissions', 'fields');

it('should not have permission to edit the resource with specific permission')
    ->expect(fn () => Field::make(null, 'id')->editPermission('properties.view')->withPermissions(Field::CONTEXT_EDIT, new Property())->model(new Property())->toColumn())
    ->toHaveKey('enabled', true)
    ->group('permissions', 'fields');

it('should not have permission to view details for the resource')
    ->expect(fn () => Field::make(null, 'id')->withPermissions(Field::CONTEXT_DETAIL, new Property())->model(new Property())->toColumn())
    ->toHaveKey('enabled', true)
    ->group('permissions', 'fields');

it('should not have have permission to view the resource with specific permission')
    ->expect(function () {
        $this->user->revokePermissionTo('properties.view');

        return Field::make(null, 'id')->detailPermission('properties.admin')->withPermissions(Field::CONTEXT_DETAIL, new Property())->model(new Property())->toColumn();
    })
    ->toHaveKey('enabled', false)
    ->group('permissions', 'fields');

it('should have permission to edit the resource with permissions disabled')
    ->expect(fn () => Field::make(null, 'id')->withoutPermissions()->withPermissions(Field::CONTEXT_EDIT, new Property())->model(new Property())->toColumn())
    ->toHaveKey('enabled', true)
    ->group('permissions', 'fields');

it('should have permission to create the resource')
    ->expect(fn () => Field::make(null, 'id')->withPermissions(Field::CONTEXT_CREATE, new Property())->model(new Property())->toColumn())
    ->toHaveKey('enabled', true)
    ->group('permissions', 'fields');

it('should not have have permission to edit the resource with specific permission')
    ->expect(function () {
        $this->user->revokePermissionTo('properties.create');

        return Field::make(null, 'id')->createPermission('properties.admin')->withPermissions(Field::CONTEXT_CREATE, new Property())->model(new Property())->toColumn();
    })
    ->toHaveKey('enabled', false)
    ->group('permissions', 'fields');
