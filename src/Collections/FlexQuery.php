<?php
namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FlexQuery
{
    /**
     * Without pagination
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function withoutPagination(): self
    {
        $this->paginate = false;

        return $this;
    }

    /**
     * Query
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function query(Request $request): self
    {
        $this->context === 'index' ? $this->toQuery($request) : $this->toWhereQuery($request);

        return $this;
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
     * @param Request $request
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

    protected function toWhereQuery(Request $request)
    {
        // Have we created the meta for the query from the flex resource?
        if (is_null($this->meta)) {
            $this->meta = $this->getCollectionMeta($this->flexResource);
        }

        // Request Attributes
        $attributes = $request->all();
        /**
         * @var Builder
         */
        $query = $this->preFilterQuery($attributes); // Selects, Joins, Authorization, Constraints

        $resource = $query->get();
        $this->resource = $this->collectResource($resource);
    }

    /**
     * Create query and execute possibly deferring filter options build
     *
     * @param Request $request
     * @return void
     */
    protected function toQuery(Request $request)
    {
        // Have we created the meta for the query from the flex resource?
        if (is_null($this->meta)) {
            $this->meta = $this->getCollectionMeta($this->flexResource);
        }

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
            // TODO: check for model query scope search
            $query = $this->search($query, $attributes);
        }
        // Filter
        if ($this->hasFilters($filters)) {
            $query = $this->applyFilters($query, $filters);
        }
        // Sort w/in search, w/in filter
        $query = $this->sortBy($query, $attributes);
        // Paginate
        $resource = $this->paginate ? $query->paginate($this->getPerPage($attributes))->withQueryString() : $query->get();
        $this->resource = $this->collectResource($resource);
        // We'll return filters for display
        $this->flexFilters = $filters;
    }

    protected function preFilterQuery(array $attributes): Builder
    {
        // Build Query from Selects
        $query = $this->flexModel->select(...$this->meta['selects']);

        // Joins
        if ($this->hasJoins()) {
            $query = $this->withJoins($query);
        }

        if ($this->hasWhereParams()) {
            $query = $this->withWhereParams($query);
        }

        // if( $this->hasRelations()){
        //     $query = $query->withRelations();
        // }

        // Authorization
        // if( $this->hasAuthorization()){
        //     $query = $this->authorize($query, $attributes);
        // }

        // (model query scope)
        // if( $this->hasQueryScope()){
        //     $query = $this->callQueryScope($query,$attributes);
        // }

        // Constraints
        if ($this->hasConstraint($attributes)) {
            $query = $this->constrain($query, $attributes);
        }

        return $query;
    }

    protected function getPerPage(array $attributes): int
    {
        return $attributes['perPage'] ?? $this->meta['perPage'];
    }
}
