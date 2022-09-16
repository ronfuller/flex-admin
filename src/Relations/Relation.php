<?php
namespace Psi\FlexAdmin\Relations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Psi\FlexAdmin\Collections\Flex;
use Psi\FlexAdmin\Fields\Field;

class Relation
{
    /**
     * @var array
     */
    protected array $relatedConditions;

    /**
     * Class name of the related resource
     *
     * @var string
     */
    protected string $related;

    /**
     * Determines if we load actions
     *
     * @var bool
     */
    protected $actions = false;

    public const
        TYPE_BELONGS_TO = 'belongsTo';

    public const
        TYPE_HAS_MANY = 'hasMany';

    public const
        TYPE_BELONGS_TO_MANY = 'belongsToMany';

    public const
        TYPE_HAS_ONE = 'hasOne';

    final public function __construct(public string $relationKey, public string $relation, public Model $model)
    {
    }

    public static function belongsTo(string $relationKey, Model $model)
    {
        return new static($relationKey, self::TYPE_BELONGS_TO, $model);
    }

    public static function hasOne(string $relationKey, Model $model)
    {
        return new static($relationKey, self::TYPE_HAS_ONE, $model);
    }

    public static function hasMany(string $relationKey, Model $model)
    {
        return new static($relationKey, self::TYPE_HAS_MANY, $model);
    }

    public static function belongsToMany(string $relationKey, Model $model)
    {
        return new static($relationKey, self::TYPE_BELONGS_TO_MANY, $model);
    }

    public function whenIndex(): self
    {
        $this->relatedConditions = [Field::CONTEXT_INDEX];

        return $this;
    }

    public function when(array $conditions): self
    {
        $this->relatedConditions = $conditions;

        return $this;
    }

    public function whenDetail(): self
    {
        $this->relatedConditions = [Field::CONTEXT_DETAIL];

        return $this;
    }

    public function whenDetailOrCreate(): self
    {
        $this->relatedConditions = [Field::CONTEXT_DETAIL, Field::CONTEXT_CREATE];

        return $this;
    }

    public function whenDetailorEdit(): self
    {
        $this->relatedConditions = [Field::CONTEXT_DETAIL, Field::CONTEXT_EDIT];

        return $this;
    }

    public function withActions(bool $actions): self
    {
        $this->actions = $actions;

        return $this;
    }

    public function attributes(): array
    {
        return [
            'key' => $this->relationKey,
            'type' => $this->relation,
            'conditions' => $this->relatedConditions ?? null,

        ];
    }

    public function build(Model $resource, Request $request): array
    {
        if (!$this->model->relationLoaded($this->relationKey)) {
            return [];
        }

        return match ($this->relation) {
            self::TYPE_BELONGS_TO => $this->buildBelongsTo(
                resource: $resource,
                request: $request
            ),
            self::TYPE_HAS_MANY => $this->buildHasMany(
                resource: $resource,
                request: $request
            ),
            self::TYPE_BELONGS_TO_MANY => $this->buildBelongsToMany(
                resource: $resource,
                request: $request
            ),
            self::TYPE_HAS_ONE => $this->buildHasOne(
                resource: $resource,
                request: $request
            ),
            default => throw new \Exception("Relation type {$this->relation} is not supported"),
        };
    }

    protected function buildBelongsTo(Model $resource, Request $request): array
    {
        return Flex::forDetail($resource->{$this->relationKey})->toArray($request);
    }

    protected function buildHasMany(Model $resource, Request $request): array
    {
        return Flex::forIndex(get_class($resource->{$this->relationKey}()->getRelated()))
            ->setResultQuery($resource->{$this->relationKey})
            ->toArray($request);
    }

    protected function buildBelongsToMany(Model $resource, Request $request): array
    {
        return $this->buildHasMany($resource, $request);
    }

    protected function buildHasOne(Model $resource, Request $request): array
    {
        return $this->buildBelongsTo($resource, $request);
    }
}
