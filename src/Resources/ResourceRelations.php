<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Http\Request;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Relations\Relation;

trait ResourceRelations
{
    /**
     * Include resource relations
     */
    protected bool $withRelations = true;

    /**
     * Filter relations by elements in this array
     */
    protected array $filterRelations = [];

    public function withoutRelations(): self
    {
        $this->withRelations = false;

        return $this;
    }

    protected function withRelations(): bool
    {
        return $this->withRelations && $this->context !== Field::CONTEXT_INDEX;
    }

    public function onlyRelations(array $filter): self
    {
        $this->filterRelations = $filter;

        return $this;
    }

    protected function toRelations(Request $request): array
    {
        return $this->withRelations() ? $this->getRelations(request: $request) : [];
    }

    protected function getRelations(Request $request): array
    {
        return collect($this->relations($request))
            ->mapWithKeys(function (Relation $relation) use ($request) {
                $item = $this->includeRelation($relation->relationKey) ?
                    [$relation->relationKey => $relation->build(
                        resource: $this->resource,
                        request: $request
                    )]
                    : [];

                return $item;
            })->filter()->all();
    }

    // protected function toJoins()
    // {
    //     return $this->columns
    //         ->filter(fn ($column) => ! empty($column['join']))
    //         ->unique(fn ($column) => $column['join'][0])
    //         ->values()
    //         ->map(fn ($column) => $column['join'])
    //         ->all();
    // }

    private function includeRelation(string $key): bool
    {
        return empty($this->filterRelations) ? true : in_array($key, $this->filterRelations);
    }
}
