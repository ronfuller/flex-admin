<?php
namespace Psi\FlexAdmin\Collections;

use Illuminate\Http\Request;
use Psi\FlexAdmin\Builders\FlexQueryBuilder;

trait FlexQuery
{
    /**
     * Query
     *
     * @return void
     */
    protected function query(Request $request): void
    {
        // Request Attributes
        $attributes = $request->all();

        /**
         * @var FlexQueryBuilder
         */
        /** @phpstan-ignore-next-line */
        $query = $this->model->index($attributes);          // index is on the builder , php stan can't see it

        $filters = $this->buildFilters($attributes, $query);
        // We'll return filters for display
        $this->flexFilters = $filters;

        // Either search or filter, not both
        if ($this->hasSearch($attributes)) {
            // Search
            $query->search($this->searchTerm($attributes));
        } elseif ($this->hasFilters($filters)) {
            // Filter
            $filterValues = $this->filterValues($filters);
            $query->filter($filterValues);
        }

        ['sort' => $sort, 'sortDir' => $sortDir] = $this->sortBy($attributes);

        // Sort w/in search, w/in filter
        $query->sortBy(sort: $sort, sortDir: $sortDir);

        // Paginate results
        /** @phpstan-ignore-next-line */
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

    protected function getPerPage(array $attributes): int
    {
        return $attributes['perPage'] ?? $this->meta['perPage'];
    }
}
