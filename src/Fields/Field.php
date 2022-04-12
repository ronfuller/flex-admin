<?php

namespace Psi\FlexAdmin\Fields;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string|null $component
 * @property array $display
 * @property array $attributes
 * @property array $permissions
 * @property array $meta
 *
 *
 *
 */

class Field
{
    use Makeable;
    use FieldPermissions;
    use FieldDisplay;
    use FieldAttributes;
    use FieldSort;
    use FieldValue;
    use FieldSelect;
    use FieldRender;
    use FieldSearchable;
    use FieldModel;
    use FieldFilter;
    use FieldRelation;

    public const
        CONTEXT_INDEX = 'index',
        CONTEXT_DETAIL = 'detail',
        CONTEXT_EDIT = 'edit',
        CONTEXT_CREATE = 'create';

    // only default contexts
    public const CONTEXTS = [
        self::CONTEXT_INDEX,
        self::CONTEXT_DETAIL,
        self::CONTEXT_EDIT,
        self::CONTEXT_CREATE,
    ];

    public const CONTEXT_PERMISSIONS = [
        self::CONTEXT_INDEX => '{entity}.view-any',
        self::CONTEXT_DETAIL => '{entity}.view',
        self::CONTEXT_EDIT => '{entity}.edit',
        self::CONTEXT_CREATE => '{entity}.create',
    ];

    public const
        FILTER_VALUE = "value",
        FILTER_RANGE = "range",
        FILTER_DATE_RANGE = "date-range",
        FILTER_LTE = "lte",
        FILTER_GTE = "gte",
        FILTER_BETWEEN = "between";

    public const FILTER_TYPES =
    [
        self::FILTER_VALUE,
        self::FILTER_RANGE,
        self::FILTER_DATE_RANGE,
        self::FILTER_LTE,
        self::FILTER_GTE,
        self::FILTER_BETWEEN,
    ];

    /**
     * Key determines the default field name, is the primary identifier for the field
     *
     * @param string $key
     */
    final public function __construct(public string $key)
    {
        $this->setDefaults();
    }

    /**
     * Sets the context for the field
     *
     * @param string $context
     * @return self
     */
    public function context(string $context): self
    {
        $this->component = $this->componentForContext($context);
        // We may have disabled through permissions, don't re-enabled with display context, context can only disable
        $this->meta['enabled'] = $this->meta['enabled'] ? $this->displayContext($context) : false;

        return $this;
    }

    public function model(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function toMeta(array|null $modelMeta = null): array
    {
        // Meta information for the primary model for the field
        $this->modelMeta = $modelMeta ?? $this->modelMeta($this->model);

        // If this field is on another model, we need that meta
        if ($this->onModel) {
            $this->onModelMeta = $this->modelMeta(new $this->onModel());
        }

        return array_merge(
            $this->meta,
            [
                'panel' => $this->panel,
                'render' => $this->render,
                'component' => $this->component,
                'key' => $this->key,
                'select' => $this->getSelect(),
                'sort' => $this->getColumn(),
                'column' => $this->getColumn(),
                'defaultSort' => $this->defaultSort,
                'sortDir' => $this->sortDir,
                'searchType' => $this->searchType,
                'filterType' => $this->filterType,
                'addToValues' => $this->addToValues,
                'join' => $this->join(),
            ]
        );
    }

    public function toAttributes(): array
    {
        return [...['key' => $this->key, 'name' => $this->meta['name'], 'panel' => $this->panel], ...$this->attributes];
    }

    public function toValue(array $attributes): mixed
    {
        $value = null;

        // if value is a string , just get the attribute
        if (is_string($this->value)) {
            $value = $attributes[$this->key] ?? $this->model->getAttributeValue($this->value);
        }

        // Array, create associative array
        if (is_array($this->value)) {
            $value = collect($this->value)->mapWithKeys(function ($value) use ($attributes) {
                return [$value => $attributes[$value] ?? $this->model->getAttributeValue($value)];
            })->all();
        }
        // callable
        if (is_callable($this->value)) {
            $value = call_user_func($this->value, $this->model);
        }

        return $value ?? ($this->default ?? null);
    }

    public function toArray(array $attributes): array
    {
        return [
            'attributes' => $this->toAttributes(),
            'value' => $this->toValue($attributes),
        ];
    }

    protected function setDefaults(): void
    {
        $this->setDefaultDisplay();
        $this->setDefaultComponents();
        $this->setDefaultRender();
        $this->setDefaultSearchType();
        $this->setDefaultName();
        $this->setDefaultLabel();
        $this->setDefaultValue();
        $this->setDefaultPermissions();
        $this->setDefaultFilter();
    }
}
