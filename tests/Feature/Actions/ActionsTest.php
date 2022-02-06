<?php


use Illuminate\Support\Facades\Route as Route;
use Psi\FlexAdmin\Actions\Action;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Tests\Feature\Actions\ActionWrapper;
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
    ->expect(Action::make('view-website'))
    ->not()
    ->toBeNull();

it('should be enabled by default')
    ->expect(Action::make('view-website')->toArray())
    ->toHaveKey('enabled', true);

it('should have a slug')
    ->expect(Action::make('view-website')->toArray())
    ->toHaveKey('slug', 'view-website');

it('should have default attributes')
    ->expect(Action::make('view-website')->toArray()['attributes'])
    ->toMatchArray([
        'disabled' => false,
        'asEvent' => true,
        'confirm' => false,
        'confirmText' => '',
        'divider' => false,
    ]);

it('should have confirm text')
    ->expect(Action::make('view-website')->confirm('Test Message')->toArray())
    ->toHaveKey('attributes.confirm', true)
    ->toHaveKey('attributes.confirmText', 'Test Message');

it('should have a before divider')
    ->expect(Action::make('view-website')->divideBefore()->toArray())
    ->toHaveCount(2)
    ->toHaveKey("0.divider", true);

it('should have an after divider')
    ->expect(Action::make('view-website')->divideAfter()->toArray())
    ->toHaveCount(2)
    ->toHaveKey("1.divider", true);

it('should have a before and after divider')
    ->expect(Action::make('view-website')->divideBoth()->toArray())
    ->toHaveCount(3)
    ->toHaveKey("0.divider", true)
    ->toHaveKey("2.divider", true);

it('should have a title')
    ->expect(Action::make('view-website')->title('Test Action')->toArray())
    ->toHaveKey('attributes.title', 'Test Action');

it('should have an icon')
    ->expect(Action::make('view-website')->icon('mdi-account')->toArray())
    ->toHaveKey('attributes.icon', 'mdi-account');

it('should have a url')
    ->expect(Action::make('view-website')->url('https://pacificscreening.com')->toArray()['attributes'])
    ->toMatchArray([
        'url' => 'https://pacificscreening.com',
        'target' => '_blank',
        'external' => true,
        'asEvent' => false,
    ]);

it('should have a url with target self')
    ->expect(Action::make('view-website')->url('https://pacificscreening.com', "_self")->toArray()['attributes'])
    ->toMatchArray([
        'url' => 'https://pacificscreening.com',
        'target' => '_self',
        'external' => true,
        'asEvent' => false,
    ]);

it('should have a route', function () {
    Route::resource('tests', TestController::class);
    $attributes = Action::make('view-website')->route('tests.show', 'get', ['test' => 1])->toArray()['attributes'];
    expect($attributes)
        ->toMatchArray([
            'external' => false,
            'asEvent' => false,
            'target' => '_self',
        ])
        ->url->toContain('/tests/1');
});
it('should merge attributes')
    ->expect(fn () => Action::make('view-website')->attributes(['color' => 'blue'])->toArray()['attributes'])
    ->toHaveKey('color', 'blue');


it('should throw error on invalid route', function () {
    Route::resource('tests', TestController::class);
    expect(fn () => Action::make('view-website')->route('tests.bad-route', 'get', ['test' => 1])->toArray()['attributes'])
        ->toThrow("Could not find route");
});

// Context Tests
it('should hide from index')
    ->expect(Action::make('view-website')->hideFromIndex()->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('enabled', false);

it('should not hide from index on condition')
    ->expect(Action::make('view-website')->hideFromIndex(false)->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('enabled', true);

it('should hide from detail')
    ->expect(Action::make('view-website')->hideFromDetail()->toArray(Field::CONTEXT_DETAIL))
    ->toHaveKey('enabled', false);

it('should hide from create')
    ->expect(Action::make('view-website')->hideFromCreate()->toArray(Field::CONTEXT_CREATE))
    ->toHaveKey('enabled', false);

it('should hide from edit')
    ->expect(Action::make('view-website')->hideFromEdit()->toArray(Field::CONTEXT_EDIT))
    ->toHaveKey('enabled', false);

it('should hide from detail when grouped')
    ->expect(Action::make('view-website')->grouped()->toArray(Field::CONTEXT_DETAIL))
    ->toHaveKey('enabled', false);

it('should be enabled for indexing when grouped')
    ->expect(Action::make('view-website')->grouped()->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('enabled', true);

it('should be enabled when withDisabled is set')
    ->expect(Action::make('view-website')->withDisabled()->hideFromIndex()->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('enabled', true);

it('should have disabled attribute when withDisabled is set')
    ->expect(Action::make('view-website')->withDisabled()->hideFromIndex()->toArray(Field::CONTEXT_INDEX))
    ->toHaveKey('attributes.disabled', true);

// Permissions Tests
it('should be disabled based on permissions')
    ->expect(fn () => Action::make('view-website')->permission('properties.view-every')->toArray())
    ->toHaveKey('enabled', false);

it('should be enable based on permissions')
    ->expect(fn () => Action::make('view-website')->permission('properties.view-any')->toArray())
    ->toHaveKey('enabled', true);

it('should be true when ignoring permissions')
    ->expect(fn () => Action::make('view-website')->permission('properties.view-every')->withoutPermissions()->toArray())
    ->toHaveKey('enabled', true);

// view-website canAct returns false on property model
it('should be disabled based on model canAct')
    ->expect(fn () => Action::make('view-website')->permission('properties.view-any')->toArray(Field::CONTEXT_INDEX, $this->property))
    ->toHaveKey('enabled', false);

// view-company canAct returns true on property model
it('should be enabled based on model canAct')
    ->expect(fn () => Action::make('view-company')->permission('properties.view-any')->toArray(Field::CONTEXT_INDEX, $this->property))
    ->toHaveKey('enabled', true);

// view-company canAct returns true on property model
it('should be enabled based when the model does not have a canAct method')
    ->expect(fn () => Action::make('view-company')->permission('properties.view-any')->toArray(Field::CONTEXT_INDEX, $this->user))
    ->toHaveKey('enabled', true);


it('should have the type of the extended class')
    ->expect(UrlAction::make('view-website'))
    ->not()
    ->toBeNull();

it('should set withDisabled')
    ->expect(ActionWrapper::make('view-website')->withDisabled()->getWithDisabled())->toBeTrue();

it('should set withDisabled returning instance of Action')
    ->expect(Action::make('view-website')->withDisabled())->toBeInstanceOf(Action::class);
