<?php

namespace Psi\FlexAdmin\Fields;

trait FieldFilter
{
    /**
     * Default filter type
     *
     * @var string
     */
    protected $filterType;

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function filterable(string $filterType = null): self
    {
        $this->attributes['filterable'] = true;
        $this->filterType = $filterType ?? $this->filterType;

        return $this;
    }

    protected function setDefaultFilter()
    {
        $this->filterType = self::FILTER_VALUE;
    }
}
