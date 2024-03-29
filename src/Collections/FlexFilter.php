<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Psi\FlexAdmin\Filters\Filter;

trait FlexFilter
{
    /**
     * Checks for any filter with a value set
     */
    protected function hasFilters(array $filters): bool
    {
        return $this->withFilters && collect($filters)->contains(fn ($filter) => ! is_null($filter['value']));
    }

    /**
     * Gets the filters from the resource w/out building options and sets values based on attributes or cache
     */
    protected function getFilters(array $attributes): array
    {
        $filters = collect($this->meta['filters']);
        $this->flexLog(message: 'Get Filters', context: $filters->all());

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
            if ($attributes['filter'] === 'reset') {
                $this->filtersClearSession();
            } else {
                $filters = $this->filtersFromAttributes($filters, $attributes);
                $this->filtersToSession($filters);
            }
        } else {
            if ($this->filtersUseSession()) {
                $filters = $this->filtersFromSession();
            }
        }

        return $filters->all();
    }

    protected function filtersUseSession(): bool
    {
        return data_get(config('flex-admin.filter') ?? [], 'session_cache', false);
    }

    protected function cacheKey(): string
    {
        return (string) str((string) str((string) str(get_class($this->resource))->snake()->replace('_', '-'))->replace('\\', '-').'-'.(string) str(url()->current())->replace(':', '')->replace('.', '-')->replace('/', '-'))->slug();
    }

    protected function filtersFromSession(): Collection
    {
        return collect($this->filtersUseSession() ? session($this->cacheKey(), []) : []);
    }

    protected function filtersToSession(Collection $filters): void
    {
        if ($this->filtersUseSession()) {
            $lifetime = data_get(config('flex-admin.filter') ?? [], 'session_cache_lifetime', 60);
            session([$this->cacheKey() => $filters->all()], now()->addMinutes($lifetime));
        }
    }

    protected function filtersClearSession(): void
    {
        if ($this->filtersUseSession()) {
            session()->forget($this->cacheKey());
        }
    }

    /**
     * Update filter values from the input attributes
     */
    protected function filtersFromAttributes(Collection $filters, array $attributes): Collection
    {
        $this->flexLog(message: 'Parsing Filter Attributes', context: $attributes);

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
     */
    protected function buildFilters(array $attributes, $query): array
    {
        $filters = $this->getFilters($attributes);
        $this->flexLog(message: 'Build Filters', context: $filters);

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
                        'is_active' => ! is_null($filterItem['value']) || (isset($item['default']) && $item['default'] !== $filterItem['value']),
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
        return $filter['name'].':'.$filter['value'][$filter['optionValue']];
    }

    public static function parseFilter(array $attributes): array
    {
        $delimiter = config('flex-admin.filter.delimiter');
        // Filter params come in with the format param1:value1;param2:value2        // colon, semicolon cannot exists in param values
        $filterParts = \explode($delimiter, \urldecode($attributes['filter']));

        return collect($filterParts)->mapWithKeys(fn ($part) => [(string) Str::of($part)->before(':')->trim() => self::valueOf((string) Str::of($part)->after(':')->trim())])->all();
    }

    private static function valueOf(string $value)
    {
        return is_numeric($value) ? (Str::of($value)->contains('.') ? (float) $value : (int) $value) : $value;
    }
}
