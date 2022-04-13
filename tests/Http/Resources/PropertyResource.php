<?php
namespace Psi\FlexAdmin\Tests\Http\Resources;

use Illuminate\Http\Request;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Filters\Filter;
use Psi\FlexAdmin\Panels\Panel;
use Psi\FlexAdmin\Relations\Relation;
use Psi\FlexAdmin\Resources\Flexible;
use Psi\FlexAdmin\Resources\Resource;
use Psi\FlexAdmin\Tests\Models\Company;
use Psi\FlexAdmin\Tests\Models\Unit;

/**
 *  ======================
 *  (new Resource($model))
 *  ->withContext( $context )
 *  ->withKeys( $keys )
 *
 * ===== ACTIONS
 *  ->withoutDefaultActions()
 *  ->withDefaultActions( $actions )
 *  ->withoutActions()
 *
 *  ==== RELATIONS
 *  ->withoutRelations()
 *  ->onlyRelations( $relations )
 *
 *  ==== OUTPUT
 *  ->toMeta()
 *  ->toArray( $request )
 *  ->toFields()
 *
 */

class PropertyResource extends Resource implements Flexible
{
    protected array $resourceFields = [];
    protected array $resourceFilters = [];
    protected array $resourceActions = [];

    /**
     * Add a field to the fields array
     *
     * @param Field $field
     * @return \Psi\FlexAdmin\Tests\Http\Resources\PropertyResource
     */
    public function addField(Field $field): self
    {
        array_push($this->resourceFields, $field);

        return $this;
    }

    /**
     * Add a filter to the filters array
     *
     * @param Filter $filter
     * @return \Psi\FlexAdmin\Tests\Http\Resources\PropertyResource
     */
    public function addFilter(Filter $filter): self
    {
        array_push($this->resourceFilters, $filter);

        return $this;
    }

    /**
     * Create fields for resource
     *
     * @param array|null|null $cols input list of columns enabled for the resource in context, null is prior to column availability
     * @return array
     */
    public function fields(array|null $keys = null): array
    {
        /*=====
          Field::make(array|null $keys, $key)   // key is usually the column name for the model
                                                // Use option chaining since the field will return null when the field name is not present in the array of valid keys
          ==== ATTRIBUTES ====
            ?->name()                   // optional name for the field, default will be the same as the key
            ->attributes($attributes)   // appends attributes on the field
            ->copyable()        // sets the copyable attribute to true, default is false
            ->selectable()      // sets the selectable attribute to true, default is false
            ->constrainable()   // the field can be the source for a constraint. Constraints are Query params in the form ?name=value where name is the field name
            ->sortable()        // the field can be used in sort
            ->icon($icon)       // sets an attribute icon
            ->readonly()        // sets readonly attribute to true, default is false
            ->hidden()          // sets attribute hidden to true, default is false
            ->valueOnly()       // the field will not have a render component, render will be false, component will be null
            ->addToValues()     // the field [ key => value ] should be included in the values object
          ==== DISPLAY CONTROL ===
            ->indexOnly()       // Field is enabled only for index context
            ->detailOnly()      // Field is enabled only for detail context
            ->editOnly()        // Field is enabled only for edit context
            ->createOnly()      // Field is enabled only for create context
            ->hideFromIndex()   // Disabled from index, other contexts are valid, only enabled attribute
            ->hidefromDetail()  // Disabled from detail context, only effects enabled attribute
            ->hideFromEdit()    // Disabled form edit context, only effects enabled attribute
            ->hideFromCreate()  // Disabled from create context, only effects enabled attribute
        ==== FILTER ===
           ->filterable($filterType) // Indicates field is filterable, specify type of of filter (i.e. value, range)
        ==== PERMISSIONS ===
            ->withoutPermissions() // Ignore permissions when rendering to array and enabling field
            ->indexPermission($permission)  // Specific permission to use with index context to enable field, default is view-any (i.e. users.view-any)
            ->detailPermission($permission) // Specific permission to use with detail context to enable field, default is view (i.e. users.view)
            ->createPermission($permission) // Specific permission to use with create context to enable field, default is edit (i.e. users.edit)
            ->editPermission($permission)   // Specific permission to use with edit cotnext to enable field, default is create (i.e. users.create)
            ->withPermissions($context,$model) // Enables the field based on the context and model plural name
        === Relation ===
            ->on(Model)                     // indicates the field is on a related model via BelongsTo relationship
        === Render ===
            ->component($component)         // specify the default component to use for rendering
            ->panel($panel)                 // group the field with the panel specified
            ->createComponent($component)   // Specific component to use for create context
            ->detailComponent($component)   // Specific component to use in detail context
            ->editComponent($component)     // Specific component to use in edit context
            ->indexComponent($component)    // Specific component for index context
        === Search ===
            ->searchable($type)             // indicates field is searchable by full text or partial (i.e. starts with term '$term%') or exact, default is 'full'
        === Sort ===
            ->defaultSort($sortDir)         // sets the field to be the default sort field and input direction
        === Value ===
            ->default( $value )             // sets a default value for the field
            ->value( $value )               // sets the field value, can be a callable function
         =====*/

        // KEYS NEED TO BE SMART WITH DATA VALUES
        $fields = [
            Field::make($keys, 'id')
                ?->name('propertyId')
                ->constrainable()
                ->valueOnly(),

            Field::make($keys, 'company_id')
                ?->name('companyId')
                ->hideFromIndex()
                ->valueOnly(),

            Field::make($keys, 'name')
                ?->selectable()
                ->sortable()
                ->defaultSort('desc')
                ->searchable(),

            Field::make($keys, 'created_at')
                ?->filterable('date-range')
                ->selectable()
                ->icon('mdi-domain'),

            Field::make($keys, 'updated_at')
                ?->filterable()
                ->selectable()
                ->icon('mdi-calendar')
                ->component('html-field'),

            Field::make($keys, 'color')
                ?->filterable()
                ->searchable()
                ->sortable()
                ->constrainable()
                ->select('options->color')
                ->icon('mdi-palette'),

            Field::make($keys, 'status')
                ?->filterable()
                ->searchable('exact')
                ->sortable()
                ->constrainable()
                ->icon('mdi-check-circle'),

            Field::make($keys, 'type')
                ?->filterable()
                ->sortable()
                ->constrainable()
                ->searchable('partial')
                ->icon('mdi-door-open'),

            Field::make($keys, 'company')
                ?->filterable()
                ->on(Company::class)
                ->select('id')
                ->valueOnly(),

            Field::make($keys, 'companyName')
                ?->on(Company::class)
                ->select('name')
                ->icon('mdi-domain'),

            Field::make($keys, 'companyEmployees')
                ?->on(Company::class)
                ->select('settings->employees')
                ->icon('mdi-domain'),
        ];

        return array_merge($fields, $this->resourceFields);
    }

    public function relations(Request $request): array
    {
        return [
            Relation::belongsTo('company')
                ->whenDetailorEdit()
                ->as(
                    Flex::forDetail(Company::class)
                ),

            Relation::hasMany('units')
                ->whenDetailorEdit()
                ->as(
                    Flex::forDetail(Unit::class)
                ),
        ];
    }

    public function actions(): array
    {
        return [];
    }

    public function panels(): array
    {
        return [
            Panel::make('empty'),
        ];
    }

    public function filters(): array
    {
        $filters = [
            Filter::make('company')
                ->fromFunction()
                ->option('id', 'name')
                ->itemValue(fn ($value) => Company::select('id', 'name')->find($value)->toArray()),
            Filter::make('type')->default('small')->fromColumn(),
            Filter::make('color')->default('blue')->fromAttribute(),
            Filter::make('created_at')->fromAttribute(),
        ];

        return array_merge($filters, $this->resourceFilters);
    }

    public function wrapResourcePermission(string $slug)
    {
        return $this->resourcePermission($slug);
    }

    public function wrapResourceRoute(string $slug)
    {
        return $this->resourceRoute($slug);
    }

    public function wrapResourceTitle(string $slug)
    {
        return $this->resourceTitle($slug);
    }
}
