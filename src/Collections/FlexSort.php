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
        ['sort' => $sort, 'sortDir' => $sortDir, 'name' => $sortName] = $this->meta['sort'];

        // See if there are order by attribute names in the attributes
        if ($this->hasSort($attributes)) {
            ['sort' => $sort, 'name' => $sortName] = $this->getSort($attributes);
            $sortDir = $this->getSortDirection($attributes);
        }

        $this->flexSort = $this->buildSort($sortName, $sortDir);
        $query = $query->orderBy($sort, $sortDir);

        return $query;
    }

    protected function buildSort(string $sortName, string $sortDir)
    {
        return [
            $this->getSortConfig('attribute') => $sortName,
            $this->getSortConfig('direction.attribute') => $this->getSortConfig('direction.flag') ? $sortDir === $this->getSortConfig('direction.flag') : $sortDir,
        ];
    }

    protected function hasSort(array $attributes): bool
    {
        $sortAttribute = config('flex-admin.sort.attribute') ?? 'sort';

        return isset($attributes[$sortAttribute]);
    }

    protected function getSortConfig(string $param): mixed
    {
        return data_get(config('flex-admin.sort'), $param);
    }

    protected function getSort(array $attributes): array
    {
        $name = $attributes[$this->getSortConfig('attribute')];
        $sort = collect($this->meta['columns'])->firstWhere('name', $name)['sort'];

        return compact('sort', 'name');
    }

    protected function getSortDirection(array $attributes): string
    {
        $dirConfig = $this->getSortConfig('direction');
        $dir = $attributes[$dirConfig['attribute']] === 'true' ? true : false;
        $flag = $dirConfig['flag'];

        if (is_null($flag)) {
            return in_array($attributes[$dirConfig['attribute']], ['asc', 'desc']) ? $attributes[$dirConfig['attribute']] : throw new \Exception("Invalid sort direction {$dir}");
        }
        // Flag is the truthy value
        return  $dir ? $flag : ($flag === 'desc' ? 'asc' : 'desc');
    }
}
