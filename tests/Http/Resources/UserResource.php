<?php

namespace Psi\FlexAdmin\Tests\Http\Resources;

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Resources\Flexible;
use Psi\FlexAdmin\Resources\Resource;

class UserResource extends Resource implements Flexible
{
    /**
     * Create fields for resource
     *
     * @param  array|null|null  $cols input list of columns enabled for the resource in context, null is prior to column availability
     * @return array
     */
    public function fields(array|null $keys = null): array
    {
        return  [
            Field::make($keys, 'id')
                ?->name('userId')
                ->valueOnly(),

        ];
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
}
