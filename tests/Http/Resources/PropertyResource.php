<?php

namespace Psi\FlexAdmin\Tests\Http\Resources;

use Illuminate\Http\Request;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Fields\Panel;
use Psi\FlexAdmin\Filters\Filter;
use Psi\FlexAdmin\Resources\Flexible;
use Psi\FlexAdmin\Resources\Resource;
use Psi\FlexAdmin\Tests\Models\Company;

class PropertyResource extends Resource implements Flexible
{
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
            ?->name()           // optional name for the field, default will be the same as the key
            ->attributes($attributes) // appends attributes on the field
            ->copyable()        // sets the copyable attribute to true, default is false
            ->selectable()      // sets the selectable attribute to true, default is false
            ->constrainable()   // the field can be the source for a constraint. Constraints are Query params in the form ?name=value where name is the field name
            ->sortable()        // the field can be used in sort
            ->icon($icon)       // sets an attribute icon
            ->readonly()        // sets readonly attribute to true, default is false
            ->hidden()          // sets attribute hiddent to true, default is fals
            ->valueOnly()       // the field will not have a render component, render will be false, component will be null
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
            ->on(Model)             // indicates the field is on a related model via BelongsTo relationship
        === Render ===
            ->component($component) // specify the default component to use for rendering
            ->panel($panel)         // group the field with the panel specified
            ->createComponent($component)   // Specific component to use for create context
            ->detailComponent($component)   // Specific component to use in detail context
            ->editComponent($component)     // Specific component to use in edit context
            ->indexComponent($component)    // Specific component for index context
        === Search ===
            ->searchable($type)    // indicates field is searchable by full text or partial (i.e. starts with term '$term%') or exact, default is 'full'
        === Sort ===
            ->defaultSort($sortDir)        // sets the field to be the default sort field and input direction
        === Value ===
            ->
         =====*/
        // KEYS NEED TO BE SMART WITH DATA VALUES
        return [
            Field::make($keys, 'id')
                ?->name('propertyId')
                ->constrainable()
                ->valueOnly(),

            Field::make($keys, 'name')
                ?->selectable()
                ->sortable()
                ->defaultSort('desc')
                ->searchable(),

            Field::make($keys, 'created_at')
                ?->filterable()
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
    }

    public function relations(Request $request): array
    {
        return [
            // Relation::belongsTo('company')
            //     ->whenDetailorEdit()
            //     ->asDetail(Flex::for(Company::class)),
            // Flex::hasMany('units')->whenDetailorEdit()->for(Unit::class)
            //     Relation::make('company' => Flex::for() )->when(Field::CONTEXT_DETAIL);
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
        return [
            Filter::make('company')
                ->fromFunction()
                ->option('id', 'name')
                ->itemValue(fn ($value) => Company::select('id', 'name')->find($value)->toArray()),
            Filter::make('type')->default(['label' => 'Small', 'value' => 'small'])->fromColumn(),
            Filter::make('color')->default('blue')->fromAttribute(),
        ];
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
