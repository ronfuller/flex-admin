<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Http\Request;
use Psi\FlexAdmin\Fields\Field;

trait ResourceRelations
{
    /**
     * Include resource relations
     *
     * @var bool
     */
    protected bool $withRelations = true;

    public function withoutRelations(): self
    {
        $this->withRelations = false;

        return $this;
    }

    protected function withRelations(): bool
    {
        return $this->withRelations && $this->context !== Field::CONTEXT_INDEX;
    }

    protected function toRelations(Request $request): array
    {
        return $this->relations($request);
    }

    protected function toJoins()
    {
        return $this->columns
            ->filter(fn ($column) => ! empty($column['join']))
            ->unique(fn ($column) => $column['join'][0])
            ->values()
            ->map(fn ($column) => $column['join'])
            ->all();
    }
}
