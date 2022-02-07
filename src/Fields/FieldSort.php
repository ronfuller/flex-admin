<?php

namespace Psi\FlexAdmin\Fields;

trait FieldSort
{
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
