<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Support\Arr;
use Psi\FlexAdmin\Actions\Action;

trait ResourceActions
{
    public function withoutActions(): self
    {
        $this->withActions = false;

        return $this;
    }

    /**
     * Determines if we should return actions
     *
     * @return bool
     */
    protected function withActions(): bool
    {
        return $this->withActions;
    }

    /**
     * Creates actions for the resource
     *
     * @return array
     */
    protected function toActions(): array
    {
        $defaultActions = $this->defaultActions();

        return collect(array_merge($defaultActions, $this->actions()))
            ->map(fn ($action) => $action->toArray())
            ->filter(fn ($action) => $action['enabled'])
            ->values()
            ->all();
    }

    /**
     * Creates default actions
     *
     * @return array
     */
    protected function defaultActions(): array
    {
        return collect(
            [
                Action::make('view', (in_array('view', $this->actions)))
                    ?->icon($this->theme['icon-view'])
                    ->attributes(Arr::only($this->theme, ['icon-color', 'color']))
                    ->title($this->resourceTitle('view'))
                    ->route(...$this->resourceRoute('view'))
                    ->permission($this->resourcePermission('view'))
                    ->hideFromDetail(),

                Action::make('edit', (in_array('edit', $this->actions)))
                    ?->icon($this->theme['icon-edit'])
                    ->attributes(Arr::only($this->theme, ['icon-color', 'color']))
                    ->title($this->resourceTitle('edit'))
                    ->route(...$this->resourceRoute('view'))
                    ->permission($this->resourcePermission('view'))
                    ->hideFromEdit(),

                Action::make('create', (in_array('create', $this->actions)))
                    ?->icon($this->theme['icon-create'])
                    ->attributes(Arr::only($this->theme, ['icon-color', 'color']))
                    ->title($this->resourceTitle('create'))
                    ->route(...$this->resourceRoute('create'))
                    ->permission($this->resourcePermission('delete'))
                    ->hideFromIndex()
                    ->hideFromCreate(),

                Action::make('delete', (in_array('delete', $this->actions)))
                    ?->icon($this->theme['icon-delete'])
                    ->attributes(Arr::only($this->theme, ['icon-color', 'color']))
                    ->title($this->resourceTitle('delete'))
                    ->route(...$this->resourceRoute('delete'))
                    ->permission($this->resourcePermission('delete'))
                    ->hideFromCreate(),
            ]
        )->filter()->values()->all();
    }
}
