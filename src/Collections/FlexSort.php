<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;

trait FlexSort
{
    public function sortBy(Builder $query, array $attributes): Builder
    {
        if (empty($this->meta['sort'])) {
            throw new \Exception("Error. Default sort is required for resource.");
        }

        // Get the default sort
        ['sort' => $sort, 'sortDir' => $sortDir] = $this->meta['sort'];

        // See if there are order by attribute names in the attributes
        if ($this->hasSort($attributes)) {
            $sort = $this->getSort($attributes);
            $sortDir = $this->getSortDirection($attributes);
        }
        $query = $query->orderBy($sort, $sortDir);

        return $query;
    }

    protected function hasSort(array $attributes): bool
    {
        $sortAttribute = config('flex-admin.sort.attribute') ?? 'sort';

        return isset($attributes[$sortAttribute]);
    }

    protected function getSort(array $attributes): string
    {
        $sortConfig = config('flex-admin.sort');
        $sortName = $attributes[$sortConfig['attribute']];
        $sort = collect($this->meta['columns'])->firstWhere('name', $sortName)['sort'];

        return $sort;
    }

    protected function getSortDirection(array $attributes): string
    {
        $dirConfig = config('flex-admin.sort.direction');
        $dir = $attributes[$dirConfig['attribute']];
        $flag = $dirConfig['flag'];
        if (is_null($flag)) {
            return in_array($dir, ['asc', 'desc']) ? $dir : throw new \Exception("Invalid sort direction {$dir}");
        }
        // Flag is the truthy value
        return (bool) $dir ? $flag : ($flag === 'desc' ? 'asc' : 'desc');
    }
}
