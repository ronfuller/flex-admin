<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Http\Request;

/**
 * @codeCoverageIgnore
 */
trait ResourceFlexible
{
    public function filters(): array
    {
        return [];
    }

    public function relations(Request $request): array
    {
        return [];
    }

    public function fields(?array $keys = null): array
    {
        return [];
    }

    public function actions(): array
    {
        return [];
    }

    public function panels(): array
    {
        return [];
    }
}
