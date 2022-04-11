<?php
namespace Psi\FlexAdmin\Fields;

trait FieldValue
{
    /**
     * Enable a null value
     *
     * @var bool
     */
    protected $nullValue = false;

    /**
     * Determines if the field should be added to resource values array
     *
     * @var bool
     */
    public $addToValues = false;

    /**
     * Default value if not set
     *
     * @var mixed
     */
    protected $default;

    /**
     * Value for the Resource
     *
     * @var string | array | callable
     **/
    protected $value;

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
     * @codeCoverageIgnore
     */
    public function append(string $append): self
    {
        $this->value = $this->value ? $this->value . ' ' . $append : $this->value;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function default(mixed $value): self
    {
        $this->default = $value;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     * @codeCoverageIgnore
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
