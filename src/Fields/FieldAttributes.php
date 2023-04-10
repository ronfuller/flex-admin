<?php

namespace Psi\FlexAdmin\Fields;

use Illuminate\Support\Str;
use Psi\FlexAdmin\Fields\Enums\DisplayContext;

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
        'selectable' => false,
        'filterable' => false,
        'searchable' => false,
        'copyable' => false,
        'align' => 'left',
        'hidden' => false,
        'readonly' => false,
    ];

    /**
     * @var array | callable
     */
    public $attributes = [];

    /**
     * @var array | callable
     */
    public $contextAttributes = [
        DisplayContext::INDEX->value => [],
        DisplayContext::DETAIL->value => [],
        DisplayContext::EDIT->value => [],
        DisplayContext::CREATE->value => [],
    ];

    /**
     * @var callable
     */
    protected $attributesFn = null;

    /**
     * Determines if we should callback a user function to get attributes
     */
    protected bool $hasCallableAttributes = false;

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
    public function label(string $label): self
    {
        $this->meta['label'] = $label;

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
    public function attributes(array|callable $attributes): self
    {
        if (is_callable($attributes)) {
            $this->attributesFn = $attributes;
            $this->hasCallableAttributes = true;
        } else {
            $this->attributes = [...$this->attributes, ...$attributes];
        }

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function attribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function indexAttribute(string $key, mixed $value): self
    {
        $this->contextAttributes[DisplayContext::INDEX->value][$key] = $value;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function detailAttribute(string $key, mixed $value): self
    {
        $this->contextAttributes[DisplayContext::DETAIL->value][$key] = $value;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function createAttribute(string $key, mixed $value): self
    {
        $this->contextAttributes[DisplayContext::CREATE->value][$key] = $value;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function editAttribute(string $key, mixed $value): self
    {
        $this->contextAttributes[DisplayContext::EDIT->value][$key] = $value;

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
     */
    protected function setDefaultName(): void
    {
        $this->meta['name'] = (string) Str::of($this->key)->camel();
        $this->meta['field'] = $this->meta['name']; // Quasar wants field key for row name
    }

    /**
     * Sets the default attribute label
     */
    protected function setDefaultLabel(): void
    {
        $this->meta['label'] = (string) Str::of($this->key)->kebab()->replace('_', ' ')->replace('-', ' ')->title();
    }
}
