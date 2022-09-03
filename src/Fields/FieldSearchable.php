<?php

namespace Psi\FlexAdmin\Fields;

trait FieldSearchable
{
    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function searchable(): self
    {
        $this->meta['searchable'] = true;

        return $this;
    }
}
