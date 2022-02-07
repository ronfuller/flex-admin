<?php

namespace Psi\FlexAdmin\Tests\Feature\Collections;

use Psi\FlexAdmin\Collections\FlexFilter;
use Psi\FlexAdmin\Filters\Filter;

class FlexFilterWrapper
{
    use FlexFilter;

    protected $meta;
    protected $defaultFilters = true;

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
        $this->meta = [
            'filters' => [
                Filter::make('company')->fromFunction()->option('id', 'name'),
                Filter::make('type')->default(['label' => 'Small', 'value' => 'small'])->fromColumn(),
                Filter::make('color')->default('blue')->fromAttribute(),
            ],
        ];

        return $this->getFilters($attributes);
    }
}
