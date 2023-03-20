<?php

namespace Psi\FlexAdmin\Panels;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Panel
{
    /**
     * Title for panel
     */
    protected string $title;

    /**
     * Attributes for the panel
     */
    protected array $attributes;

    /**
     * Ordered fields belonging to panel
     */
    protected array $fields = [];

    final public function __construct(public string $key)
    {
        $this->title = $this->title ?? $this->defaultTitle();
    }

    public static function make(...$args)
    {
        return new static(...$args);
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function icon(string $icon): self
    {
        $this->attributes['icon'] = $icon;

        return $this;
    }

    public function attributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes ?? [], $attributes);

        return $this;
    }

    public function field(array $field): self
    {
        array_push($this->fields, $field);

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            [
                'key' => $this->key,
                'title' => $this->title,
                'fields' => $this->fields,
                'enabled' => count($this->fields) > 0,
            ],
            Arr::except($this->attributes ?? [], ['key', 'title', 'fields', 'enabled'])
        );
    }

    protected function defaultTitle(): string
    {
        return (string) Str::of($this->key)->title()->replace('_', ' ');
    }
}
