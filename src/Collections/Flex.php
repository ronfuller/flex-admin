<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\CollectsResources;
use Illuminate\Support\Str;
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
     * @var array
     */
    protected $meta;

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

        // TODO:  ADD WITH PERMISSIONS HERE, DELAY META ??
        $this->meta = $resource->withContext($context)->toMeta($this->flexModel);
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
        $class = config('flex-admin.resource_path') . "\\" . Str::afterLast($modelClass, '\\') . "Resource";

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

    /**
     * Transform the resource into a JSON array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Get Meta HERE????

        if (is_null($this->resource)) {
            // We haven't executed the query if we have a null resource
            $this->query($request);
        }

        return [
            // TODO: only need pagination in index context
            'pagination' => $this->toPagination(),
            // TODO: only need columns in index context
            'columns' => $this->meta['columns'],
            // Resource rows index, resource for other context
            'data' => $this->toData($request),
            // TODO: only need filters in index context
            'filters' => $this->flexFilters,
            // Applied Filter
            // applied filter comes from request attributes or cache

        ];
    }

    /**
     * Create the transformed resource
     *
     * @param Request $request
     * @return array
     */
    protected function toData(Request $request): array
    {
        return $this->collection->map(function ($resource) use ($request) {
            return $resource->withContext($this->context)->withKeys($this->meta['keys'])->toArray($request);
        })->all();
    }
}
