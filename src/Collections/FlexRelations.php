<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;

trait FlexRelations
{
    public function hasJoins()
    {
        return ! empty($this->meta['joins'] ?? []);
    }

    public function withJoins(Builder $query)
    {
        collect($this->meta['joins'])->each(function ($join) use (&$query) {
            $query = $query->join(...$join);
        });

        return $query;
    }
}
