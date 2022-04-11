<?php
namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\CollectsResources;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Resources\Resource;

class Flex extends Resource
{
    use CollectsResources;
    use FlexQuery;
    use FlexSearch;
    use FlexConstraint;
    use FlexSort;
    use FlexFilter;
    use FilterDateRange;
    use FlexFor;
    use FlexRelations;
    use FlexCache;
    use FlexScope;
    use FlexParams;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects;

    /**
     * The mapped collection instance.
     *
     * @var \Illuminate\Support\Collection
     */
    public $collection;

    /**
     * Meta Information on the Collection including columns, selects, sort, keys
     *
     * @var array|null
     */
    protected $meta = null;

    /**
     * Request
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Determines if we paginate
     *
     * @var bool
     */
    protected bool $paginate = true;

    /**
     * Constraints to always include in query
     *
     * @var array
     */
    protected array|null $constraints = null;

    /**
     * Filter built up from request scope query , default, or cache
     *
     * @var array
     */
    protected array $filter = [];

    /**
     * Instantiated model
     *
     * @var Model
     */
    public Model $flexModel;

    /**
     * Array of filters including updated value
     *
     * @var array
     */
    public array $flexFilters = [];

    /**
     * Determines if we build filter options immediately
     *
     * @var bool
     */
    protected bool $deferFilters = true;

    /**
     * Determines if we load default filter values from the resource
     *
     * @var bool
     */
    protected bool $defaultFilters = true;

    /**
     * @var array
     */
    protected array $flexSort = [];

    /**
     * @var array
     */
    protected array $whereParams = [];

    /**
     * Inertia Page Component
     *
     * @var string|null
     */
    public ?string $page = null;
    /**
     * Determines if we should cache meta values
     *
     * @var bool
     */
    protected bool $shouldCacheMeta = true;

    protected Resource $flexResource;

    public const CONTROL_COLUMNS = ['enabled', 'filterable', 'constrainable', 'searchable', 'selectable', 'select', 'sort', 'column', 'defaultSort', 'sortDir', 'searchType', 'filterType', 'addToValues', 'join', 'render'];

    /**
     * Create a flex collection instance
     *
     * @param  string  $model
     * @param string $context
     * @param Resource $resource
     * @return void
     */
    final public function __construct(string $model, string $context, Resource $resource = null)
    {
        $this->flexModel = new $model();
        $this->context = $context;

        if (is_null($resource)) {
            $this->collects = $this->collects();
            /**
             * @var \Psi\FlexAdmin\Resources\Resource
             */
            $resource = new $this->collects(null);
        }
        // Validate context against list of contexts
        if (!in_array($context, Field::CONTEXTS)) {
            throw new \Exception("Unknown context {$context}");
        }
        $this->flexResource = $resource;
    }

    /**
     * Set the Inertia Page Component
     *
     * @param string $page
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function page(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get the resource that this resource collects.
     *
     * @return string|null
     */
    protected function collects()
    {
        if ($this->collects) {
            return $this->collects;
        }
        $modelClass = get_class($this->flexModel);
        $class = config('flex-admin.resource_path') . '\\' . Str::afterLast($modelClass, '\\') . 'Resource';

        return class_exists($class) ? $class : throw new \Exception("Could not find resource for {$modelClass}");
    }

    /**
     * Return the count of items in the resource collection.
     *
     * @return int
     */
    #[\ReturnTypeWillChange]
    public function count()
    {
        return $this->collection->count();
    }

    public function toResponse($request): \Illuminate\Http\JsonResponse | \Inertia\Response
    {
        if ($request->wantsJson()) {
            return response()->json($this->toArray($request));
        } else {
            return Inertia::render($this->page, $this->toArray($request));
        }
    }

    /**
     * Transform the resource into a JSON array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->context === 'index') {
            // Building filters won't build paginated data query results, build filters for deferred filters
            return $request->boolean('build-filters') ? $this->toQueryFilters($request) : $this->toIndexQuery($request);
        } else {
            return $this->toDataQuery($request);
        }
    }

    /**
     * Return results of a data query
     *
     * @param Request $request
     * @return array
     */
    protected function toDataQuery(Request $request): array
    {
        // Resource here is the collected resource instance
        if (is_null($this->resource)) {
            // We haven't executed the query if we have a null resource
            $this->query($request);
        }
        return [
            'data' => $this->collection->isEmpty() ? [] : $this->toData($request)[0]
        ];
    }

    /**
     * Return results of a data query
     *
     * @param Request $request
     * @return array
     */
    protected function toIndexQuery(Request $request): array
    {
        // Resource here is the collected resource instance
        if (is_null($this->resource)) {
            // We haven't executed the query if we have a null resource
            $this->query($request);
        }
        $pagination = $this->toPagination(sort: $this->flexSort);

        return [
            // TODO: only need pagination in index context
            'pagination' => $pagination,
            'rowsPerPageOptions' => data_get($pagination, 'rowsPerPageOptions'),
            // TODO: only need columns in index context
            'columns' => $this->toColumns(),
            'visibleColumns' => $this->visibleColumns(),
            // Resource rows index, resource for other context
            'rows' => $this->collection->isEmpty() ? [] : $this->toData($request),
            // TODO: only need filters in index context
            'filters' => $this->flexFilters,
        ];
    }

    protected function toColumns(): array
    {
        return collect($this->meta['columns'])->map(fn ($columns) => Arr::except($columns, self::CONTROL_COLUMNS))->all();
    }

    protected function visibleColumns(): array
    {
        return collect($this->meta['columns'])->filter(fn ($col) => $col['render'])->values()->map(fn ($col) => $col['name'])->all();
    }

    /**
     * Create the transformed resource
     *
     * @param Request $request
     * @return array
     */
    protected function toData(Request $request): array
    {
        // use the first resource in the collection to build actions
        /**
         * @var Resource
         */
        $resource = $this->collection->first();
        $actions = $resource->toActions(context: $this->context);

        // We'll pass actions to the resource to build the array of data
        return $this->collection->map(function (Resource $resource) use ($request, $actions) {
            return $resource
                ->withContext($this->context)
                ->withKeys($this->meta['keys'])
                ->withActions($actions)
                ->toArray($request);
        })->all();
    }
}
