<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Psi\FlexAdmin\Fields\Field;

trait ResourceColumns
{
    public function columns(): Collection
    {
        return collect($this->fields(null))->map(function (Field $field) {
            return $field->withPermissions($this->context, $this->model)->model($this->model)->context($this->context)->toColumn();
        })->filter(fn (array $field) => $field['enabled']);
    }

    public function renderable(): array
    {
        return $this->columns->filter(fn ($column) => $column['render'])->all();
    }

    public function selects(): array
    {
        return $this->columns->pluck('select')->all();
    }

    public function sort(): array
    {
        $defaultSort = $this->columns->firstWhere('defaultSort', true);

        return $defaultSort ? Arr::only($defaultSort, ['key', 'name', 'sort', 'sortDir']) : [];
    }

    public function keys(): array
    {
        return $this->columns->pluck('key')->all();
    }

    public function constraints(): array
    {
        return $this->columns
            ->filter(fn ($column) => $column['constrainable'])
            ->values()
            ->map(fn ($column) => Arr::only($column, ['key', 'column', 'name']))
            ->all();
    }

    public function searches(): array
    {
        return $this->columns
            ->filter(fn ($column) => $column['searchable'])
            ->values()
            ->map(fn ($column) => Arr::only($column, ['key', 'column', 'searchType']))
            ->all();
    }

    public function sorts(): array
    {
        return $this->columns
            ->filter(fn ($column) => $column['sortable'])
            ->values()
            ->map(fn ($column) => Arr::only($column, ['key', 'name', 'sort', 'sortDir']))
            ->all();
    }

    public function filterables(): array
    {
        return $this->columns
            ->filter(fn ($column) => $column['filterable'])
            ->values()
            ->map(fn ($column) => Arr::only($column, ['key', 'name', 'column', 'filterType']))
            ->all();
    }
}
