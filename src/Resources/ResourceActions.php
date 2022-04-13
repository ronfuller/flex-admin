<?php
namespace Psi\FlexAdmin\Resources;

use Illuminate\Support\Arr;
use Psi\FlexAdmin\Actions\Action;
use Psi\FlexAdmin\Fields\Field;

trait ResourceActions
{
    /**
     * Include Actions with the resource
     *
     * @var bool
     */
    protected bool $withActions = true;

    /**
     * Default actions for every resource
     *
     * @var array
     */
    protected array $defaultActions = ['view', 'edit', 'create', 'delete'];

    protected array|null $actions = null;

    /**
     * Creates a resource without actions
     *
     * @return \Psi\FlexAdmin\Resources\Resource
     */
    public function withoutActions(): self
    {
        $this->withActions = false;

        return $this;
    }

    /**
     * Creates a resource without actions
     *
     * @return \Psi\FlexAdmin\Resources\Resource
     */
    public function withoutDefaultActions(): self
    {
        $this->defaultActions = [];

        return $this;
    }

    /**
     * Determines if we should return actions
     *
     * @return \Psi\FlexAdmin\Resources\Resource
     */
    public function withDefaultActions(array $defaultActions = null): self
    {
        // TODO: validate that the array of default actions is in the default list
        if (!collect($defaultActions)->every(fn ($action) => \in_array($action, ['view', 'edit', 'create', 'delete']))) {
            throw new \Exception('Invalid default actions. Must be one of view,edit,create,delete');
        }
        $this->defaultActions = $defaultActions ?? $this->defaultActions;

        return $this;
    }

    /**
     * Returns the actions for the resource with specified permissions and context set
     *
     * @return array
     */
    public function toActions(string $context = Field::CONTEXT_INDEX): array
    {
        $defaultActions = $this->defaultActions();

        return collect(array_merge($defaultActions, $this->actions()))
            ->map(function (Action $action) use ($context) {
                return $action->toArray(context: $context);
            })
            ->filter(fn ($action) => $action['enabled'])
            ->values()
            ->all();
    }

    protected function transformActions(array $actions): array
    {
        return collect($actions)->map(function ($action) {
            $action['attributes'] = Arr::except($action['attributes'], ['route', 'divider']);

            return Arr::only($action, ['slug', 'attributes']);
        })->all();
    }

    /**
     * withActions
     *
     * @param array $actions
     * @return \Psi\FlexAdmin\Resources\Resource
     */
    protected function withActions(array $actions): self
    {
        $this->actions = collect($actions)->map(function ($action) {
            // See if we need to build the route Url from the resource params/values
            if (!empty(data_get($action, 'attributes.route'))) {
                data_set($action, 'attributes.url', $this->buildActionRouteUrl(data_get($action, 'attributes.route')));
            }
            // If we need to check on the model and we haven't already disabled the action
            if ($action['canAct'] && $action['enabled']) {
                // need to call the can resource method
                $action['enabled'] = $this->resource->canAct($action['slug']);
                data_set($action, 'attributes.disabled', !$action['enabled']);
            }

            return $action;
        })
            // We may have filtered based on capability, remove only if we don't want to show disabled items
            ->filter(fn ($action) => $action['enabled'] || ($action['withDisabled']))
            ->values()
            ->all();

        return $this;
    }

    /**
     * Build Action Route URL
     *
     * @param array $routeData
     * @return string
     */
    protected function buildActionRouteUrl(array $routeData): string
    {
        $params = collect($routeData['params'])->mapWithKeys(function ($param) {
            return [$param['name'] => $this->resource->getAttribute($param['field'])];
        })->all();

        return route($routeData['name'], $params);
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
                Action::make('view', (in_array('view', $this->defaultActions)))
                    ?->icon($this->theme['icon-view'])
                    ->attributes(Arr::only($this->theme, ['icon-color', 'color']))
                    ->title($this->resourceTitle('view'))
                    ->route(...$this->resourceRoute('view'))
                    ->permission($this->resourcePermission('view'))
                    ->hideFromDetail(),

                Action::make('edit', (in_array('edit', $this->defaultActions)))
                    ?->icon($this->theme['icon-edit'])
                    ->attributes(Arr::only($this->theme, ['icon-color', 'color']))
                    ->title($this->resourceTitle('edit'))
                    ->route(...$this->resourceRoute('view'))
                    ->permission($this->resourcePermission('view'))
                    ->hideFromEdit(),

                Action::make('create', (in_array('create', $this->defaultActions)))
                    ?->icon($this->theme['icon-create'])
                    ->attributes(Arr::only($this->theme, ['icon-color', 'color']))
                    ->title($this->resourceTitle('create'))
                    ->route(...$this->resourceRoute('create'))
                    ->permission($this->resourcePermission('delete'))
                    ->hideFromIndex()
                    ->hideFromCreate(),

                Action::make('delete', (in_array('delete', $this->defaultActions)))
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
