<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

trait FlexSearch
{
    protected function search(Builder $query, array $attributes): Builder
    {
        $term = $this->searchTerm($attributes);
        /** @phpstan-ignore-next-line */
        return $this->model->search($term);     // Search is on the Query Builder on the model, PHPStan can't see it
    }

    /**
     * Determines if the search key is found in the attribute list
     *
     * @param  array  $attributes
     * @return bool
     */
    protected function hasSearch(array $attributes): bool
    {
        return Arr::has($attributes, $this->searchAttribute());
    }

    protected function searchAttribute(): string
    {
        return config('flex-admin.search.attribute') ?? 'search';
    }

    protected function searchTerm(array $attributes): string
    {
        return strtolower($attributes[$this->searchAttribute()]);
    }
}
