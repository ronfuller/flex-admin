<?php

use Illuminate\Support\Facades\Route as Route;
use Psi\FlexAdmin\Actions\Action;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Http\Actions\UrlAction;
use Psi\FlexAdmin\Tests\Http\Controllers\TestController;
use Psi\FlexAdmin\Tests\Models\Property;
use Psi\FlexAdmin\Tests\Models\User;

beforeEach(function () {
    $this->property = Property::factory()->create(
        [
            'created_at' => '2020-01-19 12:00:01',
            'name' => 'Test Property',
        ]
    );
    $this->user = User::factory()->create(
        [
            'permissions' => ['properties.view-any'],
        ]
    );
    actingAs($this->user);
});

it('should make a resource action')
    ->expect(fn () => Action::make('view-website'))
    ->not()
    ->toBeNull()
    ->group('actions');

it('should be enabled by default')
    ->expect(fn () => Action::make('view-website')->toArray())
    ->toHaveKey('enabled', true)
    ->group('actions');

it('should have a slug')
    ->expect(fn () => Action::make('view-website')->toArray())
    ->toHaveKey('slug', 'view-website')
    ->group('actions');

it('should have a default title')
    ->expect(fn () => Action::make('view-website')->toArray())
    ->toHaveKey('attributes.title', 'View Website')
    ->group('actions');

it('should have default attributes')
    ->expect(fn () => Action::make('view-website')->toArray()['attributes'])
    ->toMatchArray([
        'disabled' => false,
        'asEvent' => true,
        'confirm' => false,
        'confirmText' => '',
        'divider' => false,
    ])
    ->group('actions');

it('should have confirm text')
    ->expect(fn () => Action::make('view-website')->confirm('Test Message')->toArray())
    ->toHaveKey('attributes.confirm', true)
    ->toHaveKey('attributes.confirmText', 'Test Message')
    ->group('actions');

it('should have a before divider')
    ->expect(fn () => Action::make('view-website')->divideBefore()->toArray())
    ->toHaveCount(2)
    ->toHaveKey('0.divider', true)
    ->group('actions');

it('should have an after divider')
    ->expect(fn () => Action::make('view-website')->divideAfter()->toArray())
    ->toHaveCount(2)
    ->toHaveKey('1.divider', true)
    ->group('actions');

it('should have a before and after divider')
    ->expect(fn () => Action::make('view-website')->divideBoth()->toArray())
    ->toHaveCount(3)
    ->toHaveKey('0.divider', true)
    ->toHaveKey('2.divider', true)
    ->group('actions');

it('should have a title')
    ->expect(fn () => Action::make('view-website')->title('Test Action')->toArray())
    ->toHaveKey('attributes.title', 'Test Action')
    ->group('actions');

it('should have an icon')
    ->expect(fn () => Action::make('view-website')->icon('mdi-account')->toArray())
    ->toHaveKey('attributes.icon', 'mdi-account')
    ->group('actions');

it('should have a url')
    ->expect(fn () => Action::make('view-website')->url('https://pacificscreening.com')->toArray()['attributes'])
    ->toMatchArray([
        'url' => 'https://pacificscreening.com',
        'target' => '_blank',
        'external' => true,
        'asEvent' => false,
    ])
    ->group('actions');

it('should have a url with target self')
    ->expect(fn () => Action::make('view-website')->url('https://pacificscreening.com', '_self')->toArray()['attributes'])
    ->toMatchArray([
        'url' => 'https://pacificscreening.com',
        'target' => '_self',
        'external' => true,
        'asEvent' => false,
    ])
    ->group('actions');

it('should have a route', function () {
    Route::resource('tests', TestController::class);
    $routeParams = [[
        'name' => 'test',
        'field' => 'id',
    ]];
    $attributes = Action::make('view-website')->route('tests.show', 'get', $routeParams)->toArray()['attributes'];
    expect($attributes)
        ->toMatchArray([
            'route' => ['name' => 'tests.show', 'params' => $routeParams],
            'external' => false,
            'asEvent' => false,
            'target' => '_self',
        ]);
})
    ->group('actions');

it('should merge attributes')
    ->expect(fn () => Action::make('view-website')->attributes(['color' => 'blue'])->toArray()['attributes'])
    ->toHaveKey('color', 'blue')
    ->group('actions');

it('should throw error on invalid route', function () {
    Route::resource('tests', TestController::class);
    expect(fn () => Action::make('view-website')->route('tests.bad-route', 'get', ['test' => 1])->toArray()['attributes'])
        ->toThrow('Could not find route');
})
    ->group('actions');

// Context Tests
it('should hide from index')
    ->expect(fn () => Action::make('view-website')->hideFromIndex()->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('enabled', false)
    ->group('actions');

it('should not hide from index on condition')
    ->expect(fn () => Action::make('view-website')->hideFromIndex(false)->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('enabled', true)
    ->group('actions');

it('should hide from detail')
    ->expect(fn () => Action::make('view-website')->hideFromDetail()->toArray(Field::CONTEXT_DETAIL))
    ->toHaveKey('enabled', false)
    ->group('actions');

it('should hide from create')
    ->expect(fn () => Action::make('view-website')->hideFromCreate()->toArray(Field::CONTEXT_CREATE))
    ->toHaveKey('enabled', false)
    ->group('actions');

it('should hide from edit')
    ->expect(fn () => Action::make('view-website')->hideFromEdit()->toArray(Field::CONTEXT_EDIT))
    ->toHaveKey('enabled', false)
    ->group('actions');

it('should hide from detail when grouped')
    ->expect(fn () => Action::make('view-website')->grouped()->toArray(Field::CONTEXT_DETAIL))
    ->toHaveKey('enabled', false)
    ->group('actions');

it('should show in index when inline')
    ->expect(fn () => Action::make('view-website')->inline()->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('enabled', true)
    ->group('actions');

it('should be enabled for indexing when grouped')
    ->expect(fn () => Action::make('view-website')->grouped()->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('enabled', true)
    ->group('actions');

it('should be enabled when withDisabled is set')
    ->expect(fn () => Action::make('view-website')->withDisabled()->hideFromIndex()->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('enabled', true)
    ->group('actions');

it('should have disabled attribute when withDisabled is set')
    ->expect(fn () => Action::make('view-website')->withDisabled()->hideFromIndex()->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('attributes.disabled', true)
    ->group('actions');

// Permissions Tests
it('should be disabled based on permissions')
    ->expect(fn () => Action::make('view-website')->permission('properties.view-every')->toArray())
    ->toHaveKey('enabled', false)
    ->group('actions');

it('should be enable based on permissions')
    ->expect(fn () => Action::make('view-website')->permission('properties.view-any')->toArray())
    ->toHaveKey('enabled', true)
    ->group('actions');

it('should be true when ignoring permissions')
    ->expect(fn () => Action::make('view-website')->permission('properties.view-every')->withoutPermissions()->toArray())
    ->toHaveKey('enabled', true)
    ->group('actions');

// view-website canAct returns false on property model
it('should be indicate resource has canAct')
    ->expect(fn () => Action::make('view-website')->permission('properties.view-any')->toArray(Field::CONTEXT_INDEX, $this->property))
    ->toHaveKey('canAct', true)
    ->group('actions');

// view-company canAct returns true on property model
it('should be show when the model does not have a canAct method')
    ->expect(fn () => Action::make('view-company')->permission('properties.view-any')->toArray(Field::CONTEXT_INDEX, $this->user))
    ->toHaveKey('canAct', false)
    ->group('actions');

it('should have the type of the extended class')
    ->expect(fn () => UrlAction::make('view-website'))
    ->not()
    ->toBeNull()
    ->group('actions');
