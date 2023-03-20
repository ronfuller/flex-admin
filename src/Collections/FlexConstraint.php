<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;

trait FlexConstraint
{
    public function withConstraints(array $constraints): self
    {
        $this->constraints = $constraints;

        return $this;
    }

    public function hasConstraint(array $attributes)
    {
        // see if any attributes are included in the constraints
        return $this->constraints || collect(array_keys($attributes))->contains(fn ($key) => in_array($key, collect($this->meta['constraints'])->pluck('name')->all()));
    }

    /**
     * Constrain the query by the column, value from the attributes
     */
    public function constrain(Builder $query, array $attributes): Builder
    {
        $attributes = $this->constraints ? array_merge($attributes, $this->constraints) : $attributes;

        collect($this->meta['constraints'])->each(function ($constraint) use (&$query, $attributes) {
            $query = $query->when($attributes[$constraint['name']] ?? null, function ($query, $value) use ($constraint) {
                return $query->where($constraint['column'], $value);
            });
        });

        return $query;
    }
}
