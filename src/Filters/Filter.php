<?php

namespace Psi\FlexAdmin\Filters;

use function call_user_func;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use function method_exists;

class Filter
{
    public const SOURCE_FUNCTION = 'function';

    public const SOURCE_COLUMN = 'column';

    public const SOURCE_ATTRIBUTE = 'attribute';

    public mixed $value = null;

    /**
     * Callable function to get an item from the filter value
     *
     * @var callable|null
     */
    public $itemFromValue;

    /**
     * Model Query Scope to apply filter
     *
     * @var string|null
     */
    public $queryScope;

    protected mixed $default = null;

    protected mixed $item = null;

    /**
     * Type of filter { select, boolean, multi-select }
     */
    protected string $type = 'select';

    /**
     * Format for filter  { filter, type-ahead, cascade, switch}
     */
    protected string $format = 'filter';

    /**
     * Options for select filter
     */
    protected array $options = [];

    /**
     * Display label for the filter
     */
    protected string $label;

    /**
     * Attributes
     */
    protected array $attributes = [
        'optionValue' => 'value',
        'optionLabel' => 'label',
    ];

    /**
     * Meta information about the filter including column details
     */
    protected array $meta = [];

    /**
     * Source for the filter
     */
    protected ?string $source = null;

    /**
     * Information about the source
     */
    protected ?string $sourceMeta = null;

    final public function __construct(public string $name, public ?string $key = null)
    {
        // TODO: validate that overridden attributes don't contain type,name,label
        $this->setDefaults();
    }

    protected function setDefaults()
    {
        $this->label = $this->label ?? (string) Str::of($this->name)->after('.')->singular()->title()->replace('_',
            ' ')->replace('-', ' ');
        $this->key = $this->key ?? (string) Str::of($this->name)->lower();
    }

    public static function make(...$args)
    {
        return new static(...$args);
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function icon($icon): self
    {
        $this->attributes['icon'] = $icon;

        return $this;
    }

    public function boolean(): self
    {
        $this->type = 'boolean';

        return $this;
    }

    public function default(mixed $default): self
    {
        $this->value = $default;
        $this->default = $default;

        return $this;
    }

    public function value(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function withScope(string $scope): self
    {
        $this->queryScope = $scope;

        return $this;
    }

    public function itemValue(callable $itemFromValue): self
    {
        $this->itemFromValue = $itemFromValue;

        return $this;
    }

    public function fromColumn(string $column = null): self
    {
        $this->source = 'column';
        $this->sourceMeta = $column ?? (string) Str::of($this->name)->lower();

        return $this;
    }

    public function fromFunction(string $function = null): self
    {
        $this->source = 'function';
        $this->sourceMeta = $function ?? $this->name;

        return $this;
    }

    public function fromAttribute(string $attribute = null): self
    {
        $this->source = 'attribute';
        $this->sourceMeta = $attribute ?? $this->name;

        return $this;
    }

    public function attributes(array $attributes): self
    {
        if (array_intersect(['name', 'label', 'type', 'format', 'value', 'meta'], array_keys($attributes))) {
            throw new Exception('Cannot append attributes with reserved keys.');
        }
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    public function meta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * Get the item value using the callable
     */
    public function getItem(mixed $value): array
    {
        return $this->itemFromValue ? call_user_func($this->itemFromValue,
            $value) : ['label' => (string) Str::of($value)->title(), 'value' => $value];
    }

    /**
     * Set the item value using the callable
     */
    public function setItem(): self
    {
        if ($this->value) {
            $this->item = $this->itemFromValue ? call_user_func($this->itemFromValue,
                $this->value) : ['label' => (string) Str::of($this->value)->title(), 'value' => $this->value];
        }

        return $this;
    }

    public function toOptions()
    {
        return $this->options;
    }

    public function toArray()
    {
        return array_merge(
            [
                'uuid' => (string) Str::uuid(),
                'source' => $this->source,
                'sourceMeta' => $this->sourceMeta,
                'key' => $this->key,
                'type' => $this->type,
                'name' => $this->name,
                'label' => $this->label,
                'format' => $this->format,
                'value' => $this->value,
                'item' => $this->item,
                'default' => $this->default,
                'queryScope' => $this->queryScope,
                'is_active' => $this->value && $this->value !== $this->default,
                'is_default' => $this->default && $this->value === $this->default,
                'options' => $this->options,
                'meta' => $this->meta,
            ],
            $this->attributes
        );
    }

    public function build(Model $model, $query): self
    {
        if (is_null($this->source) || is_null($this->sourceMeta)) {
            throw new Exception('Cannot build filter without source set.');
        }

        switch ($this->source) {
            case self::SOURCE_COLUMN:
                $this->options = $this->optionsFromColumn($model, $query);

                break;
            case self::SOURCE_FUNCTION:
                $this->options = $this->optionsFromFunction($model, $query);

                break;
            case self::SOURCE_ATTRIBUTE:
                $this->options = $this->optionsFromAttribute($model);

                break;
        }

        return $this;
    }

    protected function optionsFromColumn(Model $model, $query): array
    {
        $column = $model->qualifyColumn($this->sourceMeta);
        $filterQuery = clone $query;
        $options = $filterQuery->select($column)->distinct()->reorder()->orderBy($column)->toBase()->get()->pluck($this->sourceMeta)->all();
        $this->option('label', 'value');

        return collect($options)->map(function ($item) {
            return [
                'label' => (string) Str::of($item)->title(),
                'value' => $item,
            ];
        })->all();
    }

    public function option(string $labelKey, string $valueKey): self
    {
        $this->attributes['optionValue'] = $valueKey;
        $this->attributes['optionLabel'] = $labelKey;

        return $this;
    }

    protected function optionsFromFunction(Model $model, $query): array
    {
        $filterQuery = clone $query;
        $method = (string) Str::of($this->sourceMeta)->title()->prepend('filter');

        if (! method_exists($model, $method)) {
            throw new Exception("Could not find filter function for filter named {$this->sourceMeta}");
        }

        return $model->{$method}($filterQuery);
    }

    protected function optionsFromAttribute(Model $model): array
    {
        $attribute = 'filter_'.$this->sourceMeta;
        $filterMutatorMethod = (string) Str::of($this->sourceMeta)->studly()->prepend('getFilter')->append('Attribute');
        if (! method_exists($model, $filterMutatorMethod)) {
            throw new Exception("Attribute missing for filter {$this->sourceMeta}. Model must include getter prefixed with filter");
        }

        return $model->getAttribute('filter_'.$this->sourceMeta);
    }
}
