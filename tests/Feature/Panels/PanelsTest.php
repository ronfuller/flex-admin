<?php


use Psi\FlexAdmin\Panels\Panel;

beforeEach(function () {
});


it('should make a panel')
    ->expect(fn () => Panel::make('details'))
    ->not()
    ->toBeNull()
    ->group('panels');

it('should have a default title')
    ->expect(fn () => Panel::make('details')->toArray())
    ->title
    ->toBe('Details')
    ->group('panels');

it('should have a default title for snake case key')
    ->expect(fn () => Panel::make('user_details')->toArray())
    ->title
    ->toBe('User Details')
    ->group('panels');

it('should have an icon')
    ->expect(fn () => Panel::make('details')->icon('mdi-account')->toArray())
    ->icon
    ->toBe('mdi-account')
    ->group('panels');

it('should have a title')
    ->expect(fn () => Panel::make('details')->title('Panel Title')->toArray())
    ->title
    ->toBe('Panel Title')
    ->group('panels');

it('should have attributes')
    ->expect(fn () => Panel::make('details')->attributes(['color' => 'blue'])->toArray())
    ->color
    ->toBe('blue')
    ->group('panels');

it('should not overwrite key attribute')
    ->expect(fn () => Panel::make('details')->attributes(['key' => 'test'])->toArray())
    ->key
    ->toBe('details')
    ->group('panels');

it('should not overwrite title attribute')
    ->expect(fn () => Panel::make('details')->attributes(['title' => 'test'])->toArray())
    ->title
    ->toBe('Details')
    ->group('panels');

it('should add fields')
    ->expect(fn () => Panel::make('details')->field(['attributes' => ['key' => 'id']])->field(['attributes' => ['key' => 'name']])->toArray())
    ->fields
    ->toBe([['attributes' => ['key' => 'id']], ['attributes' => ['key' => 'name']]])
    ->group('panels');
