<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Psi\FlexAdmin\Concerns\HasControls;
use Psi\FlexAdmin\Fields\Field;

class Resource extends JsonResource implements Flexible
{
    use HasControls;
    use ResourceColumns;
    use ResourcePagination;
    use ResourceFilters;
    use ResourceFlexible;
    use ResourceActions;
    use ResourcePanels;
    use ResourceRelations;

    /**
     * @var Model
     */
    public ?Model $model = null;

    public $defaultSort = [];

    /**
     * @var string
     */
    protected $context;

    /**
     * @var bool
     */
    protected $expansion;

    /**
     *  Array of resource keys to include in the output array. The resource keys are determined by a call on the toMeta function of the resource
     *
     * @var array
     */
    protected $keys;

    /**
     * Resource Theme
     *
     * @var array
     */
    protected $theme = [
        'color' => 'secondary',
        'icon-color' => 'primary',
        'icon-edit' => 'mdi-square-edit-outline',
        'icon-view' => 'mdi-eye',
        'icon-create' => 'mdi-plus-circle',
        'icon-delete' => 'mdi-delete',
        'panel-icon' => 'mdi-clipboard-text',
    ];

    /**
     * Specifies the context for the resource
     *
     * @return \Psi\FlexAdmin\Resources\Resource
     */
    public function withContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Determines which resource field keys are valid for the resource
     *
     * @return \Psi\FlexAdmin\Resources\Resource
     */
    public function withKeys(array $keys): self
    {
        $this->keys = $keys;

        return $this;
    }

    /**
     * Creates the meta for the resource including keys, columns, filters,sorts, pagination
     */
    public function toMeta(Model $model): array
    {
        $this->model = $model;
        $this->columns = $this->columns();

        $meta = [
            'keys' => $this->keys(),
            'columns' => $this->columns->values()->all(),
            'sort' => $this->sort(),
            'sortables' => $this->sortables(),
            'filters' => $this->toFilters(),
            'searches' => $this->searchables(),
            'perPage' => $this->perPage(),
            'perPageOptions' => $this->perPageOptions(),
            'fields' => $this->columns->mapWithKeys(fn ($col, $index) => [$col['name'] => $index])->all(),
        ];
        $this->defaultSort = $meta['sort'];

        return $meta;
    }

    public function toValues(): array
    {
        $mappedFields = $this->toMappedFields(values: true);

        return $mappedFields
            ->mapWithKeys(fn ($item, $key) => [$key => $item['value']])
            ->all();
    }

    /**
     * Creates a fields array
     */
    public function toFields(): array
    {
        $mappedFields = $this->toMappedFields()->all();

        return [...['uuid' => (string) Str::uuid()], ...$mappedFields];
    }

    public function toFieldsCollection(bool $values = false): Collection
    {
        // cast, formatted attributes from the resource, including mutated attributes
        $attributes = $this->resource->attributesToArray();

        /**
         * @var \Illuminate\Support\Collection
         */
        $fields = collect($this->fields($this->keys));

        if ($values) {
            $fields = $fields->filter(fn (?Field $field) => $field ? $field->addToValues : false);
        }
        $fields = $fields->filter()->values();

        $fieldsCollection = $fields->map(function (Field $field) use ($attributes) {
            return [
                ...[
                    'component' => $field->component,
                ],
                ...$field->model($this->resource)->toArray($attributes),
            ];
        });

        return $fieldsCollection;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Actions
        $actions = $this->withActions ? $this->actions ?? $this->withActions($this->toActions())->actions : [];
        $actions = $this->transformActions($actions);

        // Relations
        $relations = $this->withRelations() ? $this->toRelations($request) : [];

        // Fields
        $fields = $this->withFields() ? $this->toFields() : [];

        // Panels
        $panels = $this->withPanels() ? $this->toPanels($this->toFieldsCollection()) : [];

        $result = empty($fields) ? [] : $fields;
        $result['actions'] = $actions;

        $values = $this->toValues();
        $result['values'] = $values;

        if ($this->context !== Field::CONTEXT_INDEX) {
            $result['panels'] = $panels;
            $result['relations'] = $relations;
        }

        return $result;
    }

    protected function toMappedFields(bool $values = false): Collection
    {
        return $this->toFieldsCollection(values: $values)->mapWithKeys(function ($item) {
            ['attributes' => $attributes, 'value' => $value] = $item;    // get the attributes and transformed value

            return [$attributes['name'] => compact('attributes', 'value')];
        });
    }

    public function indexRoute(): string
    {
        return route($this->resourceRoute('index')[0]);
    }

    protected function withFields()
    {
        // return fields array if not using panels
        return ! $this->withPanels();
    }

    /**
     * Build the default permissions for the resource
     */
    protected function resourcePermission(string $slug): string
    {
        $pluralModel = $this->modelPluralName();

        return "{$pluralModel}.{$slug}";
    }

    /**
     * Build the default title for the resource
     */
    protected function resourceTitle(string $slug): string
    {
        $modelKey = $this->modelKeyName();
        $modelTitle = (string) Str::of($modelKey)->replace('_', ' ')->title();

        return (string) Str::of($slug)->title()->append(" {$modelTitle}");
    }

    /**
     * Create the route for the resource
     */
    protected function resourceRoute(string $slug): array
    {
        $routeKeyName = $this->resource->getRouteKeyName();
        $pluralModel = $this->modelPluralName();
        $modelKey = $this->modelKeyName();

        $slugResourceRoutes = [
            'index' => 'index',
            'view' => 'show',
            'edit' => 'edit',
            'create' => 'create',
            'delete' => 'destroy',
        ];
        $slugRouteMethods = [
            'index' => 'get',
            'view' => 'get',
            'edit' => 'get',
            'create' => 'get',
            'delete' => 'delete',
        ];
        $routeMethod = $slugRouteMethods[$slug];
        $routeName = $pluralModel.'.'.$slugResourceRoutes[$slug];
        $routeParams = in_array($slug, ['view', 'edit', 'delete']) ? [['name' => $modelKey, 'field' => $routeKeyName]] : [];

        return [$routeName, $routeMethod, $routeParams];
    }

    /**
     * Plural name for model (i.e. companies)
     */
    private function modelPluralName(): string
    {
        return (string) Str::of($this->resource->qualifyColumn('id'))->before('.')->replace('_', '-');
    }

    /**
     * Key name for model ('i.e' user_profile)
     */
    private function modelKeyName(): string
    {
        return (string) Str::of($this->modelPluralName())->singular()->replace('-', '_');
    }
}
