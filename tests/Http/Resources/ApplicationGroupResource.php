<?php

namespace Psi\FlexAdmin\Tests\Http\Resources;

use Psi\FlexAdmin\Resources\Flexible;
use Psi\FlexAdmin\Resources\Resource;

class ApplicationGroupResource extends Resource implements Flexible
{
    /**
     * Create fields for resource
     *
     * @param  array|null|null  $cols input list of columns enabled for the resource in context, null is prior to column availability
     */
    public function fields(array $keys = null): array
    {
        // KEYS NEED TO BE SMART WITH DATA VALUES
        return [];
    }

    public function relations($request): array
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

    public function filters(): array
    {
        return [];
    }

    public function wrapResourcePermission(string $slug)
    {
        return $this->resourcePermission($slug);
    }

    public function wrapResourceRoute(string $slug)
    {
        return $this->resourceRoute($slug);
    }

    public function wrapResourceTitle(string $slug)
    {
        return $this->resourceTitle($slug);
    }
}
