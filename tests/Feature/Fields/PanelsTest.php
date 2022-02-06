<?php


use Psi\FlexAdmin\Fields\Panel;

beforeEach(function () {
});


it('should make a panel')
    ->expect(fn () => Panel::make('details'))
    ->not()
    ->toBeNull();

it('should have a default title')
    ->expect(fn () => Panel::make('details')->toArray())
    ->title
    ->toBe('Details');

it('should have a default title for snake case key')
    ->expect(fn () => Panel::make('user_details')->toArray())
    ->title
    ->toBe('User Details');

it('should have an icon')
    ->expect(fn () => Panel::make('details')->icon('mdi-account')->toArray())
    ->icon
    ->toBe('mdi-account');

it('should have a title')
    ->expect(fn () => Panel::make('details')->title('Panel Title')->toArray())
    ->title
    ->toBe('Panel Title');

it('should have attributes')
    ->expect(fn () => Panel::make('details')->attributes(['color' => 'blue'])->toArray())
    ->color
    ->toBe('blue');

it('should not overwrite key attribute')
    ->expect(fn () => Panel::make('details')->attributes(['key' => 'test'])->toArray())
    ->key
    ->toBe('details');

it('should not overwrite title attribute')
    ->expect(fn () => Panel::make('details')->attributes(['title' => 'test'])->toArray())
    ->title
    ->toBe('Details');

it('should add fields')
    ->expect(fn () => Panel::make('details')->field('id')->field('name')->toArray())
    ->fields
    ->toBe(['id', 'name']);
