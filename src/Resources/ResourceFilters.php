<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Filters\Filter;

trait ResourceFilters
{
    /**
     * Include filters
     *
     * @var bool
     */
    protected bool $withFilters = true;

    public function withoutFilters(): self
    {
        $this->withFilters = false;

        return $this;
    }

    /**
     * Get a filter by name
     *
     * @param string $name
     * @return mixed
     */
    public function getFilter(string $name): mixed
    {
        return collect($this->filters())->first(fn (Filter $filter) => $filter->name === $name);
    }

    /**
     * Returns filters for the resource
     *
     * @param bool $asArrayItems , returns the items as array element instead of the Filter class object
     * @param Model|null $model  , allows for a model input to determine column information
     * @return array
     */
    public function toFilters(bool $asArrayItems = true, Model $model = null): array
    {
        if (is_null($this->model)) {
            $this->model = $model;
        }
        if (is_null($this->columns) && ! is_null($model)) {
            $this->columns = $this->columns();
        }

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
        // Return as an array of array items where the filter is flattened if we want array items. This allows cacheability
        return $asArrayItems ? collect($filters)->map(fn ($filter) => $filter->setItem()->toArray())->all() : $filters;
    }

    protected function withFilters(): bool
    {
        return $this->withFilters && $this->context === Field::CONTEXT_INDEX;
    }
}
