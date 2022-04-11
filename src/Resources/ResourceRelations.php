<?php
namespace Psi\FlexAdmin\Resources;

use Illuminate\Http\Request;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Relations\Relation;

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
        return collect($this->relations($request))->mapWithKeys(function (Relation $relation) use ($request) {
            return [$relation->relationKey => $relation->build(
                resource: $this->resource,
                request: $request
            )];
        })->all();
    }

    protected function toJoins()
    {
        return $this->columns
            ->filter(fn ($column) => !empty($column['join']))
            ->unique(fn ($column) => $column['join'][0])
            ->values()
            ->map(fn ($column) => $column['join'])
            ->all();
    }
}
