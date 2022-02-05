<?php


use Psi\FlexAdmin\Fields\Panel;

beforeEach(function () {
});


it('should make a panel')
    ->expect(Panel::make('details'))
    ->not()
    ->toBeNull();

it('should have a default title')
    ->expect(Panel::make('details')->toArray())
    ->title
    ->toBe('Details');

it('should have a default title for snake case key')
    ->expect(Panel::make('user_details')->toArray())
    ->title
    ->toBe('User Details');

it('should have an icon')
    ->expect(Panel::make('details')->icon('mdi-account')->toArray())
    ->icon
    ->toBe('mdi-account');

it('should have a title')
    ->expect(Panel::make('details')->title('Panel Title')->toArray())
    ->title
    ->toBe('Panel Title');

it('should have attributes')
    ->expect(Panel::make('details')->attributes(['color' => 'blue'])->toArray())
    ->color
    ->toBe('blue');

it('should not overwrite key attribute')
    ->expect(Panel::make('details')->attributes(['key' => 'test'])->toArray())
    ->key
    ->toBe('details');

it('should not overwrite title attribute')
    ->expect(Panel::make('details')->attributes(['title' => 'test'])->toArray())
    ->title
    ->toBe('Details');

it('should add fields')
    ->expect(Panel::make('details')->field('id')->field('name')->toArray())
    ->fields
    ->toBe(['id', 'name']);
