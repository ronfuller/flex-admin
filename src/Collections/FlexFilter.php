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
     * Checks for any filter with a value set
     *
     * @param  array  $filters
     * @return bool
     */
    protected function hasFilters(array $filters): bool
    {
        return $this->withFilters && collect($filters)->contains(fn ($filter) => !is_null($filter['value']));
    }

    /**
     * Gets the filters from the resource w/out building options and sets values based on attributes or cache
     *
     * @param  array  $attributes
     * @return array
     */
    protected function getFilters(array $attributes): array
    {
        $filters = collect($this->meta['filters']);
        $this->flexLog(message: "Get Filters", context: $filters->all());

        if (!$this->defaultFilters) {
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
     * @param  Collection  $filters
     * @param  array  $attributes
     * @return Collection
     */
    protected function filtersFromAttributes(Collection $filters, array $attributes): Collection
    {
        $attrFilter = $this->parseFilter($attributes);

        return $filters->map(function ($filter) use ($attrFilter) {
            if (isset($attrFilter[$filter['name']])) {
                // Value for the filter comes from the attributes
                $filter['value'] = $attrFilter[$filter['name']];
                $filter['item'] = $this->resource->getFilter($filter['name'])->getItem($filter['value']);
            }

            return $filter;
        });
    }

    /**
     * Build the filter options from the input filter meta
     *
     * @param  Builder  $query
     * @return array
     */
    protected function buildFilters(array $attributes, Builder $query): array
    {
        $filters = $this->getFilters($attributes);
        $this->flexLog(message: "Build Filters", context: $filters);

        // the filter items in the array should be filter class objects, not arrays
        return collect($this->meta['filters'])->map(function ($filter) use ($query, $filters) {
            $item = [
                ...$filter,
                ...['options' => $this->resource->getFilter($filter['name'])->build($this->model, $query)->toOptions()],

            ];
            $filterItem = collect($filters)->firstWhere('name', $filter['name']);
            if ($filterItem) {
                $item = [
                    ...$item,
                    ...Arr::only($filterItem, ['value', 'item']),
                    ...[
                        'is_active' => !is_null($filterItem['value']) || (isset($item['default']) && $item['default'] !== $filterItem['value']),
                        'is_default' => (isset($item['default']) && $item['default'] === $filterItem['value']),
                    ],
                ];
            }

            return $item;
        })->all();
    }

    protected function filterValues(array $filters): array
    {
        return collect($filters)->filter(fn ($filter) => $filter['value'])->mapWithKeys(fn ($filter) => [$filter['name'] => $filter['value']])->all();
    }

    /**
     * Create a filter attribute from a filter array
     *
     * @param  array  $filters
     * @return array
     */
    protected function filtersAsAttributes(array $filters): array
    {
        return [
            'filter' => collect($filters)
                ->filter(fn ($filter) => !is_null($filter['value']))
                ->map(fn ($filter) => $this->filterToAttribute($filter))
                ->join('|'),
        ];
    }

    protected function filterToAttribute(array $filter)
    {
        return $filter['name'] . ':' . $filter['value'][$filter['optionValue']];
    }

    public static function parseFilter(array $attributes): array
    {
        // Filter params come in with the format param1:value1;param2:value2        // colon, semicolon cannot exists in param values
        $filterParts = \explode(';', \urldecode($attributes['filter']));

        return collect($filterParts)->mapWithKeys(fn ($part) => [(string) Str::of($part)->before(':')->trim() => self::valueOf((string) Str::of($part)->after(':')->trim())])->all();
    }

    private static function valueOf(string $value)
    {
        return is_numeric($value) ? (Str::of($value)->contains('.') ? (float) $value : (int) $value) : $value;
    }
}
