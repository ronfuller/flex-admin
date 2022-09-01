<?php
namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FlexQuery
{
    /**
     * Query
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    protected function query(Request $request): void
    {
        // Request Attributes
        $attributes = $request->all();

        /**
         * @var Builder
         */
        $query = $this->model->index($attributes);

        if ($this->hasSearch($attributes)) {
            // Search
            $query->search($this->searchTerm($attributes));
        }

        // Sort w/in search, w/in filter
        $query->sortBy($attributes);

        // Paginate results
        $this->resultQuery = $this->paginate ? $query->paginate($this->getPerPage($attributes))->withQueryString() : $query->get();
    }

    protected function collectToResource()
    {
        // Creates a collection of resource instances
        $this->resourceCollection = $this->resource->collection($this->resultQuery);
        $this->collection = $this->resourceCollection->collection;

        // We need the ResourceCollection instance here
        $this->toPaginationMeta($this->flexSort, $this->resourceCollection);
    }

    /**
     * Query Filters
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function queryFilters(Request $request): self
    {
        $this->toQueryFilters($request);

        return $this;
    }

    /**
     * Create filters with options from query
     *
     * @param  Request  $request
     * @return array
     */
    protected function toQueryFilters(Request $request): array
    {
        // Have we created the meta for the query from the flex resource?
        if (is_null($this->meta)) {
            $this->meta = $this->getCollectionMeta($this->flexResource);
        }
        // Request Attributes
        $attributes = $request->all();
        // Selects, Joins, Authorization, Constraints
        $query = $this->preFilterQuery($attributes);
        // Filters
        return $this->flexFilters = $this->buildFilters($attributes, $query);
    }

    /**
     * Create query and execute possibly deferring filter options build
     *
     * @param  Request  $request
     * @return void
     */
    protected function toQuery(Request $request)
    {
        // Request Attributes
        $attributes = $request->all();

        /**
         * @var Builder
         */
        $query = $this->preFilterQuery($attributes); // Selects, Joins, Authorization, Constraints
        // Filters
        $filters = $this->deferFilters ? $this->getFilters($attributes) : $this->buildFilters($attributes, $query);

        // Search
        if ($this->hasSearch($attributes)) {
            // Search
            $query = $this->search($query, $attributes);
        }
        // Filter
        if ($this->hasFilters($filters)) {
            $query = $this->applyFilters($query, $filters);
        }
        // Sort w/in search, w/in filter
        $query = $query->sortBy($query, $attributes);
        // Paginate
        $resource = $this->paginate ? $query->paginate($this->getPerPage($attributes))->withQueryString() : $query->get();
        $this->resource = $this->collectResource($resource);
        // We'll return filters for display
        $this->flexFilters = $filters;
    }

    protected function getPerPage(array $attributes): int
    {
        return $attributes['perPage'] ?? $this->meta['perPage'];
    }
}
