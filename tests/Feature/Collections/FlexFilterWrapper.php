<?php

namespace Psi\FlexAdmin\Tests\Feature\Collections;

use Psi\FlexAdmin\Collections\FlexFilter;
use Psi\FlexAdmin\Filters\Filter;

class FlexFilterWrapper
{
    use FlexFilter;

    protected $meta;

    protected $defaultFilters = true;

    protected $flexResource;

    private $flexFilters;

    public function wrapParseFilter(array $attributes)
    {
        return $this->parseFilter($attributes);
    }

    public function wrapFiltersAsAttributes(array $filters)
    {
        return $this->filtersAsAttributes($filters);
    }

    public function wrapGetFilters(array $attributes)
    {
        $this->flexFilters = [
            Filter::make('company')->fromFunction()->option('id', 'name'),
            Filter::make('type')->default(['label' => 'Small', 'value' => 'small'])->fromColumn(),
            Filter::make('color')->default('blue')->fromAttribute(),
        ];
        $this->resource = $this;
        $this->meta = [
            'filters' => collect($this->flexFilters)->map(fn ($filter) => $filter->toArray())->all(),
        ];

        return $this->getFilters($attributes);
    }

    public function getFilter(string $name)
    {
        return collect($this->flexFilters)->first(function ($filter) use ($name) {
            return $filter->name === $name;
        });
    }
}
