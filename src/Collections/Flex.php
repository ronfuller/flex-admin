<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Collection as Collection;
use Inertia\Inertia;
use Psi\FlexAdmin\Concerns\HasControls;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Resources\Resource;

class Flex
{
    use HasControls;
    use FlexFor;
    use FlexQuery;
    use FlexSearch;
    use FlexSort;
    use FlexFilter;
    use FlexPagination;
    use FlexColumns;
    use FlexOptions;
    use FlexResource;
    use FlexSort;
    use FlexLogging;

    /**
     * The  flex resource class
     *
     * @var resource
     */
    public $resource;

    /**
     * Instantiated model w/out attributes, for column select only
     */
    public Model $model;

    /**
     * @var AnonymousResourceCollection
     */
    public ?AnonymousResourceCollection $resourceCollection = null;

    /**
     * @var Collection
     */
    public ?Collection $collection = null;

    /**
     * @var array
     */
    public $paginationMeta = [];

    /**
     * Meta Information on the Collection including columns, filters,  sorts, keys
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
     * Filter built up from request scope query , default, or cache
     */
    protected array $filter = [];

    /**
     * Array of filters including updated value
     */
    public array $flexFilters = [];

    protected array $flexSort = [];

    /**
     * Query results executed from a query or passed in via the set function
     */
    protected mixed $resultQuery = null;

    /**
     * Model instance for non index context
     *
     * @var Model
     */
    protected ?Model $resultModel = null;

    /**
     * Create a flex collection instance
     *
     * @param  string  $model , class name of model for for the resource
     * @return void
     */
    final public function __construct(string $model, public string $context, public ?string $resourceClassName = null)
    {
        $this->model = new $model;

        $resourceClassName = $resourceClassName ?? $this->resource();

        $this->resource = new $resourceClassName($this->model);

        $this->meta = $this->resource->withContext($context)->toMeta($this->model);
    }

    /**
     * Ability to set query results generated from an external query builder exec
     */
    public function setResultQuery(mixed $resultQuery, Request $request): self
    {
        if (! is_a($resultQuery, 'Illuminate\Pagination\LengthAwarePaginator')) {
            $this->flexFilters = $this->buildFilters(attributes: $request->all(), query: $resultQuery);
            $resultQuery = $resultQuery->paginate(10);
        }
        $this->resultQuery = $resultQuery;
        $this->paginate = is_a($this->resultQuery, 'Illuminate\Pagination\LengthAwarePaginator');

        return $this;
    }

    /**
     * Ability to set query results generated from an external query builder exec
     */
    public function setResultModel(Model $resultModel): self
    {
        $this->resultModel = $resultModel;

        return $this;
    }

    /**
     * Generates an Inertia Response
     */
    public function toResponse(Request $request): \Illuminate\Http\JsonResponse|\Inertia\Response
    {
        if ($request->wantsJson()) {
            return response()->json(['data' => $this->toArray($request)]);
        } else {
            return Inertia::render($this->page, $this->toArray($request));
        }
    }

    /**
     * Transform the resource into a JSON array.
     *
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request, array $append = [])
    {
        $this->flexLog(message: "Flex Transform for Context {$this->context} ", context: $request->all());
        $data = $this->context === Field::CONTEXT_INDEX ? $this->toIndexQuery($request) : $this->toDataQuery($request);

        $array = $this->transformer ? call_user_func_array($this->transformer, compact('data')) : $data;

        $array = [
            ...$array,
            ...$append,
        ];

        if ($this->sendToRay) {
            ray($array);
        }

        return $array;
    }

    /**
     * Return results of a data query
     */
    protected function toIndexQuery(Request $request): array
    {
        if (is_null($this->resultQuery)) {
            $this->query($request);
        }

        $this->collectToResource();

        return [
            'columns' => $this->toColumns(),
            'filters' => $this->flexFilters,
            'visibleColumns' => $this->visibleColumns(),
            'rowsPerPageOptions' => data_get($this->paginationMeta, 'rowsPerPageOptions'),
            'pagination' => $this->paginationMeta,
            'rows' => $this->toData($request),
        ];
    }

    protected function toDataQuery(Request $request): array
    {
        $resource = new $this->resource($this->resultModel);
        $actions = $resource->toActions(context: $this->context);

        return ['data' => $this->transformResource($resource, $actions, $request)];
    }

    /**
     * Create the transformed resource
     */
    protected function toData(Request $request): array
    {
        // Pull the collection from the ResourceCollection Instance
        $collection = $this->collection;

        if ($collection->isEmpty()) {
            return [];
        }

        // We'll pass actions to the resource to build the array of data
        return $collection->map(function (Resource $resource) use ($request) {
            $actions = $resource->toActions(context: $this->context);

            return $this->transformResource($resource, $actions, $request);
        })->all();
    }

    protected function transformResource(Resource $resource, array $actions, Request $request)
    {
        $data = $resource
            ->withContext($this->context)
            ->withKeys($this->meta['keys'])
            ->withActions($actions)
            ->setControls($this->getControls())         // cascade control parameters like actions, relations to the resource
            ->toArray($request);

        return $this->fieldsAsObject ? $this->transformFields($data) : $data;
    }

    public function transformFields(array $data): array
    {
        $fields = collect($data['panels'])->each(function ($panel, $index) use (&$data) {
            $fields = collect($panel['fields'])
                ->mapWithKeys(fn ($item) => [data_get($item, 'attributes.name') => $item])
                ->all();
            data_set($data, "panels.{$index}.fields", $fields);
        });

        return $data;
    }
}
