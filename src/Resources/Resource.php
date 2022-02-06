<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Fields\Panel;

class Resource extends JsonResource implements Flexible
{
    use ResourceColumns;
    use ResourcePagination;
    use ResourceFilters;
    use ResourceFlexible;
    use ResourceActions;
    use ResourcePanels;
    use ResourceRelations;

    /**
     * Instance of Eloquent Model
     *
     * @var Model
     */
    public Model | null $model = null;

    /**
     * @var string
     */
    protected $context;

    /**
     *
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
     *
     * @var int - pagination option to use for default pagination per page
     */
    protected int|null $perPage;

    /**
     *
     * @var array
     */
    protected array|null $perPageOptions;

    /**
     * Determines if we should paginate the resource
     *
     * @var bool
     */
    protected bool $paginate = true;

    /**
     * Default actions for every resource
     *
     * @var array
     */
    protected array $actions = ['view', 'edit', 'create', 'delete'];

    /**
     * Key for default panel
     *
     * @var string
     */
    protected string $defaultPanelKey = 'details';

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
     * Include Actions with the resource
     *
     * @var bool
     */
    protected bool $withActions = true;

    /**
     * Include panels with the resource
     *
     * @var bool
     */
    protected bool $withPanels = true;

    /**
     * Include filters
     *
     * @var bool
     */
    protected bool $withFilters = true;

    /**
     * Include resource relations
     *
     * @var bool
     */
    protected bool $withRelations = true;

    /**
     * Column collection
     *
     * @var \Illuminate\Support\Collection
     */
    protected $columns;

    /**
     * Specifies the context for the resource
     *
     * @param string $context
     * @return self
     */
    public function withContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * Determines which resource field keys are valid for the resource
     *
     * @param array $keys
     * @return self
     */
    public function withKeys(array $keys): self
    {
        $this->keys = $keys;

        return $this;
    }

    /**
     * Creates the meta for the resource including keys, columns, filters,sorts, pagination
     *
     * @param Model $model
     * @return array
     */
    public function toMeta(Model $model): array
    {
        $this->model = $model;
        $this->columns = $this->columns();

        $meta = [
            'keys' => $this->keys(),            // TODO: keys may need to be more than an array of resource keys, may need associative array with smart info
            'columns' => $this->renderable(),
            'selects' => $this->selects(),
            'sort' => $this->sort(),
            'sorts' => $this->sorts(),
            'filters' => $this->toFilters(),
            'joins' => $this->toJoins(),
            'searches' => $this->searches(),
            'constraints' => $this->constraints(),
            'perPage' => $this->perPage(),
            'perPageOptions' => $this->perPageOptions(),
        ];
        // TODO: validate meta, must contain a default sort, sortable can't be false if default sort, can't have multiple default sorts
        return $meta;
    }

    /**
     * Creates a fields collection
     *
     * @return Collection
     */
    public function toFields(): Collection
    {
        // cast, formatted attributes from the resource, including mutated attributes
        $attributes = $this->resource->attributesToArray();

        /**
         * @var \Illuminate\Support\Collection
         */
        $fields = collect($this->fields($this->keys))->filter()->values();      // null value for keys will return all fields unrestricted

        return $fields->map(function (Field $field) {
            return $field->context($this->context);                             // context sets enabled status
        })->filter(
            fn (Field $field) => $field->enabled()                              // display context enabled
        )->values()
            ->map(function (Field $field) use ($attributes) {
                return $field->model($this->resource)->toArray($attributes);    // get the attributes and transformed value
            });
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $fieldCollection = $this->toFields();

        // return fields
        $fields = $this->toFields()->all();

        // creates values object with name/value pairs
        $values = $this->toValues($fieldCollection);

        // return actions
        $actions = $this->withActions() ? $this->toActions() : [];

        // return relations based on context
        $relations = $this->withRelations() ? $this->toRelations($request) : [];

        // return panels based on context
        $panels = $this->withPanels() ? $this->toPanels($fieldCollection) : [];

        $base = ['fields', 'values', 'actions'];
        $args = $this->context === Field::CONTEXT_INDEX ? $base : [...$base, 'panels', 'relations'];

        return compact(...$args);
    }

    /**
     * Creates an associative array of values for the resource
     *
     * @param Collection $fieldsCollection
     * @return array
     */
    protected function toValues(Collection $fieldsCollection): array
    {
        // TODO: determine if we should add id, if not in fields. Need to guarantee values always include ID

        return $fieldsCollection
            ->filter(fn ($field) => $field['addToValues'])
            ->mapWithKeys(fn ($field) => [$field['attributes']['name'] => $field['value']])->all();
    }

    /**
     * Build the default permissions for the resource
     *
     * @param string $slug
     * @return string
     */
    protected function resourcePermission(string $slug): string
    {
        $pluralModel = $this->modelPluralName();

        return "{$pluralModel}.{$slug}";
    }

    /**
     * Build the default title for the resource
     *
     * @param string $slug
     * @return string
     */
    protected function resourceTitle(string $slug): string
    {
        $modelKey = $this->modelKeyName();
        $modelTitle = (string) Str::of($modelKey)->replace("_", " ")->title();

        return (string) Str::of($slug)->title()->append(" {$modelTitle}");
    }

    /**
     * Create the route for the resource
     *
     * @param string $slug
     * @return array
     */
    protected function resourceRoute(string $slug): array
    {
        $routeKeyName = $this->resource->getRouteKeyName();
        $pluralModel = $this->modelPluralName();
        $modelKey = $this->modelKeyName();

        $slugResourceRoutes = [
            'view' => 'show',
            'edit' => 'edit',
            'create' => 'create',
            'delete' => 'destroy',
        ];
        $slugRouteMethods = [
            'view' => 'get',
            'edit' => 'get',
            'create' => 'get',
            'delete' => 'delete',
        ];
        $routeMethod = $slugRouteMethods[$slug];
        $routeName = $pluralModel . "." . $slugResourceRoutes[$slug];
        $routeParam = in_array($slug, ['view', 'edit', 'delete']) ? [$modelKey => $this->resource->getAttribute($routeKeyName)] : [];

        return [$routeName, $routeMethod, $routeParam];
    }

    /**
     * Plural name for model (i.e. companies)
     *
     * @return string
     */
    private function modelPluralName(): string
    {
        return (string) Str::of($this->resource->qualifyColumn('id'))->before('.')->replace("_", "-");
    }

    /**
     * Key name for model ('i.e' user_profile)
     *
     * @return string
     */
    private function modelKeyName(): string
    {
        return (string) Str::of($this->modelPluralName())->singular()->replace("-", "_");
    }
}
