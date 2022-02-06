<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Support\Collection;
use Psi\FlexAdmin\Fields\Field;

trait ResourceFilters
{
    public function withoutFilters(): self
    {
        $this->withFilters = false;

        return $this;
    }

    /**
     * Creates filters from filterable data and resource filters
     *
     * @return array
     */
    protected function toFilters(): array
    {
        /**
         * @var Collection
         */
        $filterables = collect($this->filterables());
        /**
         * @var array
         */
        $filters = $this->withFilters() ? $this->filters() : [];

        collect($filters)->each(function ($filter) use ($filterables) {
            $filterable = $filterables->firstWhere('key', $filter->key);
            if (is_null($filterable)) {
                throw new \Exception("Filter for key = {$filter->key} is not filterable");
            }
            $filter->meta($filterable);
        });

        return $filters;
    }

    protected function withFilters(): bool
    {
        return $this->withFilters && $this->context === Field::CONTEXT_INDEX;
    }
}
