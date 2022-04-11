<?php

namespace Psi\FlexAdmin\Fields;

use Illuminate\Support\Str;

trait FieldAttributes
{
    /**
     * Holds the meta for the resource field
     *
     * @var array
     */
    public $meta = [
        'enabled' => true,
        'sortable' => false,
        'filterable' => false,
        'constrainable' => false,
        'searchable' => false,
        'selectable' => false,
        'copyable' => false,
        'align' => 'left',
        'hidden' => false,
        'readonly' => false,
    ];

    public $attributes = [];

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        return $this->meta['enabled'];
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function name(string $name): self
    {
        $this->meta['name'] = $name;
        $this->meta['field'] = $name;   // Quasar wants the field name in the field key

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function align(string $align): self
    {
        $this->meta['align'] = $align;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = [...$this->attributes, ...$attributes];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function meta(array $meta): self
    {
        $this->meta = [...$this->meta, ...$meta];

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function copyable(): self
    {
        $this->meta['copyable'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function selectable(): self
    {
        $this->meta['selectable'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function constrainable(): self
    {
        $this->meta['constrainable'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function sortable(): self
    {
        $this->meta['sortable'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function icon(string $icon): self
    {
        // Icon may be per resource instance
        $this->attributes['icon'] = $icon;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function readonly(): self
    {
        $this->meta['readonly'] = true;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function hidden(): self
    {
        $this->meta['hidden'] = true;

        return $this;
    }

    /**
     * Sets the default attribute name
     *
     * @return void
     */
    protected function setDefaultName(): void
    {
        $this->meta['name'] = (string) Str::of($this->key)->camel();
        $this->meta['field'] = $this->meta['name']; // Quasar wants field key for row name
    }

    /**
     * Sets the default attribute label
     *
     * @return void
     */
    protected function setDefaultLabel(): void
    {
        $this->meta['label'] = (string) Str::of($this->key)->kebab()->replace("_", " ")->replace("-", " ")->title();
    }
}
