<?php
namespace Psi\FlexAdmin\Tests\Http\Resources;

use Illuminate\Http\Request;
use Psi\FlexAdmin\Actions\Action;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Panels\Panel;
use Psi\FlexAdmin\Filters\Filter;
use Psi\FlexAdmin\Resources\Relation;
use Psi\FlexAdmin\Resources\Resource as FlexResource;

class UnitResource extends FlexResource
{
    /**
     * Create fields for resource
     *
     * @param array|null|null $cols input list of columns enabled for the resource in context, null is prior to column availability
     * @return array
     */
    public function fields(array|null $cols = null): array
    {
        return [
            Field::make($cols, 'id')
                ?->valueOnly(),
            Field::make($cols, 'created_at')
                ?->defaultSort(direction: 'desc')
                ->valueOnly(),
            Field::make($cols, 'status')
                ?->filterable(filterType: 'value')
                ->sortable(),
            Field::make($cols, 'title')
                ?->searchable()
                ->sortable(),
            Field::make($cols, 'tagline')
                ?->searchable()
                ->value(fn ($resource) => str($resource->tagline)->substr(0, 80)),
            Field::make($cols, 'rent')
                ?->filterable(),
            Field::make($cols, 'size')
                ?->filterable(filterType: 'range'),
            Field::make($cols, 'beds')
                ?->filterable(filterType: 'value')
                ->sortable(),
            Field::make($cols, 'baths')
                ?->filterable(filterType: 'value')
                ->sortable(),

            Field::make($cols, 'pets')
                ?->filterable(filterType: 'value'),
            Field::make($cols, 'garage')
                ?->filterable(filterType: 'value')
                ->align('center')
                ->component('boolean-field')
        ];
    }

    /**
     * Define relationships for the field
     *
     * @param Request $request
     * @return array
     */
    public function relations(Request $request): array
    {
        return [
        /*
            Relation::belongsTo('company')
                ->whenDetailorEdit()
                ->as(Flex::forDetail(Company::class)),
            */];
    }

    public function actions(): array
    {
        return [
        /*
            Action::make('view-website')
            */];
    }

    public function panels(): array
    {
        return [
        /*
            Panel::make('my-panel'),
            */];
    }

    public function filters(): array
    {
        return  [
            Filter::make('status')->fromColumn()->icon('mdi-list-status'),
            Filter::make('beds')->fromColumn()->icon('mdi-bed'),
            Filter::make('baths')->fromColumn()->icon('mdi-shower'),
            Filter::make('pets')->fromColumn()->icon('mdi-paw'),
        ];
    }
}
