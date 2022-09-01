<?php

declare(strict_types=1);
namespace Psi\FlexAdmin\Collections;

trait FlexResource
{
    /**
     * Get the resource class for the model
     *
     * @return string|null
     */
    protected function resource()
    {
        $modelClass = get_class($this->model);

        $class = (string) str($modelClass)->replace(config('flex-admin.model_path'), config('flex-admin.resource_path'))->append('Resource');

        return class_exists($class) ? $class : throw new \Exception("Could not find resource for {$modelClass}");
    }
}
