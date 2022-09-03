<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Lib\FlexInspect;

trait ResourceColumns
{
    /**
     * Column collection
     *
     * @var \Illuminate\Support\Collection | null
     */
    protected $columns = null;

    public function columns(): Collection
    {
        $modelMeta = (new FlexInspect($this->model))->meta;

        return collect($this->fields(null))->map(function (Field $field) use ($modelMeta) {
            return $field->withPermissions($this->context, $this->model)->model($this->model)->context($this->context)->toMeta($modelMeta);
        })->filter(fn (array $field) => $field['enabled'])->values();
    }

    public function keys(): array
    {
        return $this->columns->pluck('key')->all();
    }

    public function searchables(): array
    {
        return $this->columns
            ->filter(fn ($column) => $column['searchable'])
            ->values()
            ->map(fn ($column) => Arr::only($column, ['key', 'name', 'column']))
            ->all();
    }

    public function sort(): array
    {
        $defaultSort = $this->columns->firstWhere('defaultSort', true);

        return $defaultSort ? Arr::only($defaultSort, ['key', 'name', 'sort', 'sortDir']) : [];
    }

    public function filterables(): array
    {
        return $this->columns
            ->filter(fn ($column) => $column['filterable'])
            ->values()
            ->map(fn ($column) => Arr::only($column, ['key', 'name', 'column', 'filterType']))
            ->all();
    }

    public function sortables(): array
    {
        return $this->columns
            ->filter(fn ($column) => $column['sortable'])
            ->values()
            ->map(fn ($column) => Arr::only($column, ['key', 'name', 'sort', 'sortDir']))
            ->all();
    }
}
