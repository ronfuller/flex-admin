<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Psi\FlexAdmin\Filters\Filter;

trait FlexFilter
{
    /**
     * Without default filters
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function withoutDefaultFilters(): self
    {
        $this->defaultFilters = false;

        return $this;
    }

    /**
     * Without deferred filters
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function withoutDeferredFilters(): self
    {
        $this->deferFilters = false;

        return $this;
    }

    /**
     * Checks for any filter with a value set
     *
     * @param array $filters
     * @return bool
     */
    protected function hasFilters(array $filters): bool
    {
        return $this->withFilters && collect($filters)->contains(fn ($filter) => ! is_null($filter['value']));
    }

    /**
     * Gets the filters from the resource w/out building options and sets values based on attributes or cache
     *
     * @param array $attributes
     * @return array
     */
    protected function getFilters(array $attributes): array
    {
        $filters = collect($this->meta['filters']);

        if (! $this->defaultFilters) {
            // not using default filters then set any values to null
            $filters = $filters->map(function ($filter) {
                $filter['value'] = null;

                return $filter;
            });
        }
        // filters will include default filters with values if set

        // Determine if we are filtering from the query string
        if (isset($attributes['filter'])) {
            $filters = $this->filtersFromAttributes($filters, $attributes);
        }

        return $filters->all();
    }

    /**
     * Update filter values from the input attributes
     *
     * @param Collection $filters
     * @param array $attributes
     * @return Collection
     */
    protected function filtersFromAttributes(Collection $filters, array $attributes): Collection
    {
        $attrFilter = $this->parseFilter($attributes);

        return $filters->map(function ($filter) use ($attrFilter) {
            if (isset($attrFilter[$filter['name']])) {
                // Value for the filter comes from the attributes
                $filter['value'] = $attrFilter[$filter['name']];
                $filter['item'] = $this->flexResource->getFilter($filter['name'])->getItem($filter['value']);
            }

            return $filter;
        });
    }

    /**
     * Build the filter options from the input filter meta
     *
     * @param Builder $query
     * @return array
     */
    protected function buildFilters(array $attributes, Builder $query): array
    {
        $filters = $this->getFilters($attributes);
        // the filter items in the array should be filter class objects, not arrays
        return collect($this->meta['filters'])->map(function ($filter) use ($query, $filters) {
            $item = [
                ...$filter,
                ...['options' => $this->flexResource->getFilter($filter['name'])->build($this->flexModel, $query)->toOptions()],

            ];
            $filterItem = collect($filters)->firstWhere('name', $filter['name']);
            if ($filterItem) {
                $item = [
                    ...$item,
                    ...Arr::only($filterItem, ['value', 'item']),
                    ...[
                        'is_active' => ! is_null($filterItem['value']) && ($item['default'] !== $filterItem['value']),
                    ],
                ];
            }

            return $item;
        })->all();
    }

    /**
     * Apply the filter to the query
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        collect($filters)
            ->filter(fn ($filter) => ! is_null($filter['value']))    // only filters with a value set
            ->each(function ($filter) use (&$query) {
                $meta = $filter['meta'];
                $value = $filter['value'];
                $query = $this->filterByType($query, $value, $meta['column'], $meta['filterType']);
            });

        return $query;
    }

    /**
     * Create the filter by type of filter
     *
     * @param Builder $query
     * @param mixed $value
     * @param string $column
     * @param string $type
     * @return Builder
     */
    protected function filterByType(Builder $query, mixed $value, string $column, string $type): Builder
    {
        switch ($type) {
            case 'value':
                return $query->where($column, '=', $value);

                // TODO: Implement range filter
                // case 'range':
                //     return $query->whereIn($column, $value);
            case 'date-range':
                return $query->where($column, '>', $this->getStartDateTime($value))->where($column, '<=', $this->getEndDateTime($value));

                // TODO: implement default route with error
        }

        return $query;
    }

    /**
     * Create a filter attribute from a filter array
     *
     * @param array $filters
     * @return array
     */
    protected function filtersAsAttributes(array $filters): array
    {
        return [
            'filter' => collect($filters)
                ->filter(fn ($filter) => ! is_null($filter['value']))
                ->map(fn ($filter) => $this->filterToAttribute($filter))
                ->join('|'),
        ];
    }

    protected function filterToAttribute(array $filter)
    {
        return $filter['name'] . ':' . $filter['value'][$filter['optionValue']];
    }

    protected function parseFilter(array $attributes): array
    {
        // Filter params come in with the format param1:value1;param2:value2        // colon, semicolon cannot exists in param values
        $filterParts = \explode(';', $attributes['filter']);

        return collect($filterParts)->mapWithKeys(fn ($part) => [(string) Str::of($part)->before(':')->trim() => $this->valueOf((string) Str::of($part)->after(':')->trim())])->all();
    }

    private function valueOf(string $value)
    {
        return is_numeric($value) ? (Str::of($value)->contains('.') ? (float) $value : (int) $value) : $value;
    }
}
