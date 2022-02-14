<?php

namespace Psi\FlexAdmin\Resources;

use Illuminate\Support\Collection;
use Psi\FlexAdmin\Fields\Field;
use Psi\FlexAdmin\Panels\Panel;

trait ResourcePanels
{
    /**
     * Include panels with the resource
     *
     * @var bool
     */
    protected bool $withPanels = true;


    /**
     * Key for default panel
     *
     * @var string
     */
    protected string $defaultPanelKey = 'details';

    public function withoutPanels(): self
    {
        $this->withPanels = false;

        return $this;
    }

    protected function withPanels(): bool
    {
        return $this->withPanels && $this->context !== Field::CONTEXT_INDEX;
    }

    protected function toPanels(Collection $fieldCollection): array
    {
        $defaultPanels = $this->defaultPanels();
        $panelCollection = collect(array_merge($defaultPanels, $this->panels()));

        $panels = $this->panelsWithFields($fieldCollection, $panelCollection);

        return $panels->map(fn ($panel) => $panel->toArray())->filter(fn ($panel) => $panel['enabled'])->values()->all();
    }

    protected function defaultPanels(): array
    {
        return
            [
                Panel::make($this->defaultPanelKey)
                    ->icon($this->theme['panel-icon']),
            ];
    }

    public function panelsWithFields(Collection $fieldCollection, Collection $panelCollection): Collection
    {
        $fieldPanels = $fieldCollection->each(function ($field) use ($panelCollection) {
            $key = $field['panel'];

            if (! empty($key)) {
                $panel = $panelCollection->first(function ($item) use ($key) {
                    return $item->key === $key;
                });

                if (is_null($panel)) {
                    throw new \Exception("Could not find panel for key = {$key}");
                }
                $panel->field($field['key']);
            }
        });

        return $panelCollection;
    }
}
