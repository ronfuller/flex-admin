<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait FlexQuery
{
    public function withoutPagination(): self
    {
        $this->paginate = false;

        return $this;
    }

    public function query(Request $request): self
    {
        $this->toQuery($request);

        return $this;
    }

    public function queryFilters(Request $request): self
    {
        $this->toQueryFilters($request);

        return $this;
    }

    /**
     * Create filters with options from query
     *
     * @param Request $request
     * @return void
     */
    protected function toQueryFilters(Request $request)
    {
        // Request Attributes
        $attributes = $request->all();
        // Selects, Joins, Authorization, Constraints
        $query = $this->preFilterQuery($attributes);
        // Filters
        $this->flexFilters = $this->buildFilters($attributes, $query);
    }

    /**
     * Create query and execute possibly deferring filter options build
     *
     * @param Request $request
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
        $filters =  $this->deferFilters ?  $this->getFilters($attributes) : $this->buildFilters($attributes, $query);

        if ($this->hasFilters($filters)) {
            $query = $this->applyFilters($query, $filters);
        }
        // Search (Search w/in filters)
        if ($this->hasSearch($attributes)) {
            // Search
            // TODO: check for model query scope search
            $query = $this->search($query, $attributes);
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
