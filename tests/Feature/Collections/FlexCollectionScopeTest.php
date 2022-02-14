<?php

use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Tests\Models\Property;

it('should have an authorize scope')
    ->expect(fn () => Flex::forIndex(Property::class)->authorizeScope(scope: 'authorize')->scopes)
    ->toHaveKey('authorize', 'authorize')
    ->group('collections', 'scope');

it('should fail with invalid authorize scope', function () {
    expect(fn () => Flex::forIndex(Property::class)->authorizeScope(scope: 'invalid')->scopes)
        ->toThrow("Scope invalid does not exist");
})->group('collections', 'scope');

it('should have an order scope')
    ->expect(fn () => Flex::forIndex(Property::class)->orderScope(scope: 'order')->scopes)
    ->toHaveKey('order', 'order')
    ->group('collections', 'scope');

it('should fail with invalid order scope', function () {
    expect(fn () => Flex::forIndex(Property::class)->orderScope(scope: 'invalid')->scopes)
        ->toThrow("Scope invalid does not exist");
})->group('collections', 'scope');

it('should have a filter scope')
    ->expect(fn () => Flex::forIndex(Property::class)->filterScope(scope: 'filter')->scopes)
    ->toHaveKey('filter', 'filter')
    ->group('collections', 'scope');

it('should fail with invalid filter scope', function () {
    expect(fn () => Flex::forIndex(Property::class)->filterScope(scope: 'invalid')->scopes)
        ->toThrow("Scope invalid does not exist");
})->group('collections', 'scope');

it('should have a search scope')
    ->expect(fn () => Flex::forIndex(Property::class)->searchScope(scope: 'search')->scopes)
    ->toHaveKey('search', 'search')
    ->group('collections', 'scope');

it('should fail with invalid search scope', function () {
    expect(fn () => Flex::forIndex(Property::class)->searchScope(scope: 'invalid')->scopes)
        ->toThrow("Scope invalid does not exist");
})->group('collections', 'scope');

it('should have a index scope')
    ->expect(fn () => Flex::forIndex(Property::class)->indexScope(scope: 'index')->scopes)
    ->toHaveKey('index', 'index')
    ->group('collections', 'scope');

it('should fail with invalid index scope', function () {
    expect(fn () => Flex::forIndex(Property::class)->indexScope(scope: 'invalid')->scopes)
        ->toThrow("Scope invalid does not exist");
})->group('collections', 'scope');

it('should have a detail scope')
    ->expect(fn () => Flex::forIndex(Property::class)->detailScope(scope: 'detail')->scopes)
    ->toHaveKey('detail', 'detail')
    ->group('collections', 'scope');

it('should fail with invalid detail scope', function () {
    expect(fn () => Flex::forIndex(Property::class)->detailScope(scope: 'invalid')->scopes)
        ->toThrow("Scope invalid does not exist");
})->group('collections', 'scope');

it('should have a create scope')
    ->expect(fn () => Flex::forIndex(Property::class)->createScope(scope: 'create')->scopes)
    ->toHaveKey('create', 'create')
    ->group('collections', 'scope');

it('should fail with invalid create scope', function () {
    expect(fn () => Flex::forIndex(Property::class)->createScope(scope: 'invalid')->scopes)
        ->toThrow("Scope invalid does not exist");
})->group('collections', 'scope');

it('should have a edit scope')
    ->expect(fn () => Flex::forIndex(Property::class)->editScope(scope: 'edit')->scopes)
    ->toHaveKey('edit', 'edit')
    ->group('collections', 'scope');

it('should fail with invalid edit scope', function () {
    expect(fn () => Flex::forIndex(Property::class)->editScope(scope: 'invalid')->scopes)
        ->toThrow("Scope invalid does not exist");
})->group('collections', 'scope');
