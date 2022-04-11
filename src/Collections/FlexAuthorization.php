<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Database\Eloquent\Builder;

trait FlexAuthorization
{
    protected function hasAuthorization(): bool
    {
        return isset($this->scopes['authorize']);
    }

    protected function authorize(Builder $query, array $attributes)
    {
        $scope = $this->scopes['authorize'];

        return $query->{$scope}(
            $attributes
        );
    }
}
