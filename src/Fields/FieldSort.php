<?php

namespace Psi\FlexAdmin\Fields;

trait FieldSort
{
    /**
     * Determines if this field is the default sort by field
     *
     * @var bool
     */
    protected $defaultSort = false;

    /**
     * Sort Direction for Sort Column {asc, desc}
     *
     * @var string
     */
    protected $sortDir = null;

    /**
    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function defaultSort(string $direction = 'asc'): self
    {
        $this->defaultSort = true;
        $this->sortDir = in_array($direction, ['desc', 'asc']) ? $direction : throw new \Exception('Error in sort direction parameter');

        return $this;
    }
}
