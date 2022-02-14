<?php

namespace Psi\FlexAdmin\Relations;

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
        TYPE_BELONGS_TO = "belongsTo",
        TYPE_HAS_MANY = "hasMany",
        TYPE_BELONGS_TO_MANY = "belongsToMany",
        TYPE_HAS_ONE = "hasOne";

    final public function __construct(protected string $relationKey, protected string $relation)
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
}
