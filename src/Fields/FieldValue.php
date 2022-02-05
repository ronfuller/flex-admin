<?php

namespace Psi\FlexAdmin\Fields;

trait FieldValue
{
    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function value(string | callable | array $value): self
    {
        $this->value = $value;

        return $this;
    }

    // TODO: Add Append Functionality
    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function append(string $append): self
    {
        $this->value = $this->value ? $this->value . " " . $append : $this->value;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function default($value): self
    {
        $this->default = $value;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function nullValue(): self
    {
        $this->nullValue = true;

        return $this;
    }

    protected function setDefaultValue()
    {
        $this->value = $this->key;
    }
}
