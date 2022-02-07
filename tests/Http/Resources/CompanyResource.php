<?php

namespace Psi\FlexAdmin\Tests\Http\Resources;

use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Resources\Flexible;
use Psi\FlexAdmin\Resources\Resource;

class CompanyResource extends Resource implements Flexible
{
    protected $removedKeys = [];

    /**
     * Remove any field
     *
     * @param string $key
     * @return self
     */
    public function removeField(string $key): self
    {
        $this->removedKeys[] = $key;

        return $this;
    }

    /**
     * Create fields for resource
     *
     * @param array|null|null $cols input list of columns enabled for the resource in context, null is prior to column availability
     * @return array
     */
    public function fields(array|null $keys = null): array
    {
        $fields = [
            Field::make($keys, 'id')
                ?->name('companyId')
                ->constrainable()
                ->valueOnly(),

            Field::make($keys, 'name')
                ?->selectable()
                ->sortable()
                ->searchable(),

        ];

        return collect($fields)->filter()->filter(fn (Field $field) => ! in_array($field->key, $this->removedKeys))->values()->all();
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
