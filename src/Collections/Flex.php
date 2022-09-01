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
    /**
     * The  flex resource class
     *
     * @var Resource
     */
    public $resource;

    /**
     * Instantiated model w/out attributes, for column select only
     *
     * @var Model
     */
    public Model $model;

    /**
     *
     * @var AnonymousResourceCollection
     */
    public ?AnonymousResourceCollection $resourceCollection = null;

    /**
     * @var Collection
     */
    public ?Collection $collection = null;

    /**
     * @var array
     *
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
     *
     * @var array
     */
    protected array $filter = [];

    /**
     * Array of filters including updated value
     *
     * @var array
     */
    public array $flexFilters = [];

    /**
     * @var array
     */
    protected array $flexSort = [];

    /**
     * Query results executed from a query or passed in via the set function
     *
     * @var mixed
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
     * @param  string  $context
     * @return void
     */
    final public function __construct(string $model, public string $context)
    {
        $this->model = new $model;

        $resourceClassName = $this->resource();
        $this->resource = new $resourceClassName($this->model);
        $this->meta = $context = Field::CONTEXT_INDEX ? $this->resource->withContext($context)->toMeta($this->model) : [];
    }

    /**
     * Ability to set query results generated from an external query builder exec
     *
     * @param mixed $resultQuery
     * @return self
     */
    public function setResultQuery(mixed $resultQuery): self
    {
        $this->resultQuery = $resultQuery;
        $this->paginate = is_a($this->resultQuery, 'Illuminate\Pagination\LengthAwarePaginator');
        return $this;
    }

    /**
     * Ability to set query results generated from an external query builder exec
     *
     * @param Model $resultModel
     * @return self
     */
    public function setResultModel(Model $resultModel): self
    {
        $this->resultModel = $resultModel;

        return $this;
    }

    /**
     * Generates an Inertia Response
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse | \Inertia\Response
     */
    public function toResponse(Request $request): \Illuminate\Http\JsonResponse | \Inertia\Response
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
    public function toArray(Request $request)
    {
        return $this->context === Field::CONTEXT_INDEX ? $this->toIndexQuery($request) : $this->toDataQuery($request);
    }

    /**
     * Return results of a data query
     *
     * @param  Request  $request
     * @return array
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
     *
     * @param  Request  $request
     * @return array
     */
    protected function toData(Request $request): array
    {
        // Pull the collection from the ResourceCollection Instance
        $collection = $this->collection;

        if ($collection->isEmpty()) {
            return [];
        }
        // use the first resource in the collection to build actions
        /**
         * @var Resource
         */
        $resource = $collection->first();
        $actions = $resource->toActions(context: $this->context);

        // We'll pass actions to the resource to build the array of data
        return $collection->map(function (Resource $resource) use ($request, $actions) {
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
