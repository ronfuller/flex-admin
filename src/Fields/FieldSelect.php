<?php

namespace Psi\FlexAdmin\Fields;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait FieldSelect
{
    // TODO: Add depends field, include in selects for mutated attributes

    public function select(string $key): self
    {
        $this->select = $key;

        return $this;
    }

    protected function getSelect(): string | null
    {
        $select = $this->select ?? $this->fromModelTable();

        if (is_null($select)) {
            return null;
        }

        return $this->withQualifiedColumn($this->withAsColumn($select));
    }

    protected function getColumn(): string | null
    {
        $select = $this->select ?? $this->fromModelTable();
        if (is_null($select)) {
            return null;
        }

        return $this->withQualifiedColumn($select);
    }

    protected function fromModelTable(): string|null
    {
        return Schema::hasColumn($this->model->getTable(), $this->key) ? $this->key : null;
    }

    protected function withAsColumn(string $select): string
    {
        return Str::of($select)->contains("->") || $this->onModel ? (string) Str::of($select)->append(" as {$this->key}") : $select;
    }

    protected function withQualifiedColumn(string $select): string
    {
        return $this->onModel ? $this->qualifyOnModelColumn($select) : $this->model->qualifyColumn($select);
    }
}
