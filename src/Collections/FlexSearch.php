<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait FlexSearch
{
    protected function search(Builder $query, array $attributes): Builder
    {
        $term = $this->searchTerm($attributes);

        // If we
        if (Arr::has($this->scopes, 'search')) {
            $this->validateScope($this->scopes['search']);

            return $query->{$this->scopes['search']}($term);
        }

        collect($this->meta['searches'])->each(function ($search, $index) use (&$query, $term) {
            ['param' => $param, 'operator' => $operator, 'isJson' => $isJson, 'column' => $column] = $this->searchOptions($search, $term);
            if ($index === 0) {
                $query = $isJson ? $query->whereRaw($column, [$param]) : $query->where($column, $operator, $param);
            } else {
                $query = $isJson ? $query->orWhereRaw($column, [$param]) : $query->orWhere($column, $operator, $param);
            }
        });

        return $query;
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

    protected function searchOptions(array $search, string $term): array
    {
        $isJson = Str::contains($search['column'], '->');
        $operator = 'like';
        $param = '';

        switch ($search['searchType']) {
            case 'full':
                $param = "%{$term}%";

                break;
            case 'partial':
                $param = $isJson ? "%{$term}%" : "{$term}%";

                break;
            case 'exact':
                $param = $term;
                $operator = '=';

                break;
        }

        $column = $isJson ? $this->jsonColumn($search['column'], $operator) : $search['column'];

        return compact('param', 'column', 'operator', 'isJson');
    }

    private function jsonColumn(string $column, string $operator): string
    {
        $name = (string) Str::of($column)->afterLast('->');

        return (string) Str::of($column)->replace($name, "\"$.{$name}\"")->prepend('LOWER(')->append(") {$operator} ?");
    }
}
