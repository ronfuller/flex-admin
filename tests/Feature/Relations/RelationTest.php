<?php


use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Relations\Relation;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create(
        [
            'permissions' => ['properties.view-any'],
        ]
    );
    actingAs($this->user);
});

it('should create a belongs to relation for a resource')
    ->expect(fn () => Relation::belongsTo('company')->attributes())
    ->toHaveKey('key', 'company')
    ->toHaveKey('type', Relation::TYPE_BELONGS_TO)
    ->group('relations');

it('should create a belongs to many relation for a resource')
    ->expect(fn () => Relation::belongsToMany('company')->attributes())
    ->toHaveKey('key', 'company')
    ->toHaveKey('type', Relation::TYPE_BELONGS_TO_MANY)
    ->group('relations');

it('should create a has many relation for a resource')
    ->expect(fn () => Relation::hasMany('company')->attributes())
    ->toHaveKey('key', 'company')
    ->toHaveKey('type', Relation::TYPE_HAS_MANY)
    ->group('relations');

it('should create a detail context condition for a resource')
    ->expect(fn () => Relation::hasMany('company')->whenDetail()->attributes())
    ->toHaveKey('conditions', [Field::CONTEXT_DETAIL])
    ->group('relations');

it('should create an index context condition for a resource')
    ->expect(fn () => Relation::hasMany('company')->whenIndex()->attributes())
    ->toHaveKey('conditions', [Field::CONTEXT_INDEX])
    ->group('relations');

it('should create a detail or edit context condition for a resource')
    ->expect(fn () => Relation::hasMany('company')->whenDetailOrEdit()->attributes())
    ->toHaveKey('conditions', [Field::CONTEXT_DETAIL, Field::CONTEXT_EDIT])
    ->group('relations');

it('should create a detail or create context condition for a resource')
    ->expect(fn () => Relation::hasMany('company')->whenDetailOrCreate()->attributes())
    ->toHaveKey('conditions', [Field::CONTEXT_DETAIL, Field::CONTEXT_CREATE])
    ->group('relations');

it('should create a context condition for a resource')
    ->expect(fn () => Relation::hasMany('company')->when([Field::CONTEXT_DETAIL, Field::CONTEXT_CREATE])->attributes())
    ->toHaveKey('conditions', [Field::CONTEXT_DETAIL, Field::CONTEXT_CREATE])
    ->group('relations');

it('should create a collection for a resource')
    ->expect(fn () => Relation::hasMany('company')
        ->whenDetail()
        ->as(Flex::forIndex(Company::class)))
    ->not
    ->toBeNull()
    ->group('relations');
