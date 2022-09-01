<?php

declare(strict_types=1);
namespace Psi\FlexAdmin\Collections;

use Illuminate\Http\Resources\Json\ResourceCollection as JsonResourceCollection;

class ResourceCollection extends JsonResourceCollection
{
    /**
     * Map the given collection resource into its individual resources.
     *
     * @param  mixed  $resource
     * @return mixed
     */
    protected function collectResource($resource)
    {
        parent::collectResource($resource);
    }

    public function toArray($request)
    {
    }
}
