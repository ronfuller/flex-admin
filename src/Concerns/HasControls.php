<?php

declare(strict_types=1);

namespace Psi\FlexAdmin\Concerns;

use Illuminate\Support\Arr;
use Psi\FlexAdmin\Enums\ControlParams;

trait HasControls
{
    /**
     * Set the control parameters for actions and relations
     */
    public function setControls(array $args): self
    {
        collect(ControlParams::values())->each(function ($param) use ($args) {
            if (Arr::has($args, $param)) {
                $this->{$param} = data_get($args, $param);
            }
        });

        return $this;
    }

    public function getControls(): array
    {
        return collect(ControlParams::values())
            ->filter(fn ($param) => property_exists($this, $param))
            ->mapWithKeys(fn ($param) => [$param => $this->{$param}])
            ->all();
    }
}
