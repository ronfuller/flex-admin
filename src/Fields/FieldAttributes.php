<?php

namespace Psi\FlexAdmin\Fields;

use Illuminate\Support\Str;

trait FieldAttributes
{
    /**
     * Holds the attributes for the resource field
     *
     * @var array
     */
    public $attributes = [
        'enabled' => true,
        'sortable' => false,
        'filterable' => false,
        'constrainable' => false,
        'searchable' => false,
        'selectable' => false,
        'copyable' => false,
        'hidden' => false,
        'readonly' => false,
        'align' => 'left',
    ];

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return $this->attributes['enabled'];
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function name(string $name): self
    {
        $this->attributes['name'] = $name;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function copyable(): self
    {
        $this->attributes['copyable'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function selectable(): self
    {
        $this->attributes['selectable'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function constrainable(): self
    {
        $this->attributes['constrainable'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function sortable(): self
    {
        $this->attributes['sortable'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function icon(string $icon): self
    {
        $this->attributes['icon'] = $icon;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function readonly(): self
    {
        $this->attributes['readonly'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hidden(): self
    {
        $this->attributes['hidden'] = true;

        return $this;
    }

    /**
     * Sets the default attribute name
     *
     * @return void
     */
    protected function setDefaultName(): void
    {
        $this->attributes['name'] = (string) Str::of($this->key)->camel();
    }

    /**
     * Sets the default attribute label
     *
     * @return void
     */
    protected function setDefaultLabel(): void
    {
        $this->attributes['label'] = (string) Str::of($this->key)->kebab()->replace("_", " ")->replace("-", " ")->title();
    }
}
