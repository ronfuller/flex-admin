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
     * Callable Function to build collection/resource
     *
     * @var \Psi\FlexAdmin\Collections\Flex
     */
    public Flex $collection;

    public const
        TYPE_BELONGS_TO = 'belongsTo';

    public const
        TYPE_HAS_MANY = 'hasMany';

    public const
        TYPE_BELONGS_TO_MANY = 'belongsToMany';

    public const
        TYPE_HAS_ONE = 'hasOne';

    final public function __construct(public string $relationKey, protected string $relation)
    {
    }

    public static function belongsTo(string $relationKey)
    {
        return new static($relationKey, self::TYPE_BELONGS_TO);
    }

    public static function hasMany(string $relationKey)
    {
        return new static($relationKey, self::TYPE_HAS_MANY);
    }

    public static function belongsToMany(string $relationKey)
    {
        return new static($relationKey, self::TYPE_BELONGS_TO_MANY);
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

    public function as(Flex $collection): self
    {
        $this->collection = $collection;

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
        $foreignKey = (string) str($this->relationKey)->snake().'_id';
        $id = $resource->getAttribute($foreignKey);
        if (is_null($id)) {
            throw new \Exception("Could not locate foreign key {$foreignKey} on model");
        }

        return data_get($this->collection->byId($id)->toArray($request), 'data');
    }

    protected function buildHasMany(Model $resource, Request $request): array
    {
        $foreignKey = $resource->getForeignKey();
        $id = $resource->getKey();

        return $this->collection->where(
            column: $foreignKey,
            value: $id
        )->toArray($request);
    }

    protected function buildBelongsToMany(Model $resource, Request $request): array
    {
        return [];
    }

    protected function buildHasOne(Model $resource, Request $request): array
    {
        return [];
    }
}
