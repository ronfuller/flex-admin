<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;

trait FlexParams
{
    public function byId(int $id): self
    {
        $this->whereParams[] = [$this->flexModel->qualifyColumn('id'), '=', $id];

        return $this;
    }

    public function where(string $column, mixed $value): Flex
    {
        $this->whereParams[] = [$this->flexModel->qualifyColumn($column), '=', $value];

        return $this;
    }

    protected function hasWhereParams(): bool
    {
        return ! empty($this->whereParams);
    }

    protected function withWhereParams(Builder $query)
    {
        return $query->where($this->whereParams);
    }
}
