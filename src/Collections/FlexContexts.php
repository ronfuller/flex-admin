<?php

namespace Psi\FlexAdmin\Collections;

use Psi\FlexAdmin\Fields\Field;

trait FlexContexts
{
    public function index(): self
    {
        $this->context = Field::CONTEXT_INDEX;

        return $this;
    }

    public function detail(): self
    {
        $this->context = Field::CONTEXT_DETAIL;

        return $this;
    }

    public function edit(): self
    {
        $this->context = Field::CONTEXT_EDIT;

        return $this;
    }

    public function create(): self
    {
        $this->context = Field::CONTEXT_CREATE;

        return $this;
    }
}
