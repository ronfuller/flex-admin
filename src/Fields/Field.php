<?php

namespace Psi\FlexAdmin\Fields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

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
     * Name of component, override on child
     *
     * @var string | null
     */
    public string | null $component;

    /**
     * Defines the contexts in which the resource field is displayed
     *
     * @var array
     */
    public $display;

    /**
     * Associative array of components
     */
    public array|null $components;


    /**
     * Indicates the field should be a value only without a render component
     *
     * @var bool
     */
    public $valueOnly;

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
     * Context permissions
     *
     * @var array
     */
    public $permissions = [];

    /**
     * Field should be readonly on forms
     *
     * @var bool
     */
    public $readonly = false;

    /**
     * Enable a null value
     *
     * @var bool
     */
    public $nullValue = false;

    /**
     * Determines if the field should be added to resource values array
     *
     * @var bool
     */
    protected $addToValues = false;

    /**
     * Default value if not set
     *
     * @var mixed
     */
    public $default;

    /**
     * Associates resource with a distinct panel
     *
     * @var string
     */
    public $panel;

    /**
     * Value for the Resource
     *
     * @var string | array | callable
     **/
    protected $value;

    /**
     * Select query column
     *
     * @var string|null
     */
    protected string|null $select = null;

    /**
     * Default filter type
     *
     * @var string
     */
    protected $filterType;

    /**
     * Determines if this field is the default sort by field
     *
     * @var bool
     */
    protected $defaultSort = false;

    /**
     * Sort Direction for Sort Column {asc, desc}
     *
     * @var string
     */
    protected $sortDir = null;

    /**
     * Search type - exact, full, partial
     *
     * @var string
     */
    protected string $searchType;

    /**
     *
     * @var bool
     */
    protected $withPermissions = true;


    /**
     * Determines whether to render the field with the component
     *
     * @var bool
     */
    protected bool $render = true;

    /**
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    protected Model $model;

    /**
     * Meta information for the model
     *
     * @var array
     */
    protected array $modelMeta;


    /**
     * Related model that the field is on
     *
     * @var string|null
     */
    protected string|null $onModel = null;

    /**
     * Meta information on the related model
     *
     * @var array|null
     */
    protected array|null $onModelMeta = null;

    final public function __construct(public $key)
    {
        $this->setDefaults();
    }

    public function context(string $context): self
    {
        $this->component = $this->componentForContext($context);
        $this->attributes['enabled'] = $this->displayContext($context);

        return $this;
    }

    public function model(Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function toColumn(): array
    {
        // Meta information for the primary model for the field
        $this->modelMeta = $this->modelMeta($this->model);

        // If this field is on another model, we need that meta
        if ($this->onModel) {
            $this->onModelMeta = $this->modelMeta(new $this->onModel());
        }

        return array_merge(
            Arr::only($this->attributes, ['label', 'name', 'sortable', 'align', 'enabled', 'hidden', 'filterable', 'selectable', 'searchable', 'constrainable']),
            [
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
        return [
            'render' => $this->render,
            'component' => $this->component,
            'key' => $this->key,
            'panel' => $this->panel,
            'attributes' => $this->attributes,
            'addToValues' => $this->addToValues,
        ];
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
        return array_merge($this->toAttributes(), ['value' => $this->toValue($attributes)]);
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
