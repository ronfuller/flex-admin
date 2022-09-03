<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Collections;

use Illuminate\Support\Arr;
use Psi\FlexAdmin\Enums\ControlParams;

trait FlexColumns
{
    protected function toColumns(): array
    {
        return collect($this->meta['columns'])->map(fn ($columns) => Arr::except($columns, ControlParams::values()))->all();
    }

    protected function visibleColumns(): array
    {
        return collect($this->meta['columns'])->filter(fn ($col) => $col['render'])->values()->map(fn ($col) => $col['name'])->all();
    }
}
