<?php

namespace Psi\FlexAdmin\Collections;

use Illuminate\Contracts\Database\Eloquent\Builder;

trait FlexScope
{
    public array $scopes = [];

    protected ?array $withScopes = null;

    /**
     * Add an authorize scope
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function authorizeScope(string $scope): self
    {
        $this->validateScope($scope);
        $this->scopes['authorize'] = $scope;

        return $this;
    }

    /**
     * Add an order scope
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function orderScope(string $scope): self
    {
        $this->validateScope($scope);
        $this->scopes['order'] = $scope;

        return $this;
    }

    /**
     * Add a filter scope
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function filterScope(string $scope): self
    {
        $this->validateScope($scope);
        $this->scopes['filter'] = $scope;

        return $this;
    }

    /**
     * Add a search scope
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function searchScope(string $scope): self
    {
        $this->validateScope($scope);
        $this->scopes['search'] = $scope;

        return $this;
    }

    /**
     * Add an index scope
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function indexScope(string $scope): self
    {
        $this->validateScope($scope);
        $this->scopes['index'] = $scope;

        return $this;
    }

    /**
     * Add a detail scope
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function detailScope(string $scope): self
    {
        $this->validateScope($scope);
        $this->scopes['detail'] = $scope;

        return $this;
    }

    /**
     * Add a create scope
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function createScope(string $scope): self
    {
        $this->validateScope($scope);
        $this->scopes['create'] = $scope;

        return $this;
    }

    /**
     * Add an edit scope
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function editScope(string $scope): self
    {
        $this->validateScope($scope);
        $this->scopes['edit'] = $scope;

        return $this;
    }

    /**
     * Add additional scopes to the query
     *
     * @return \Psi\FlexAdmin\Collections\Flex
     */
    public function withScope(string|array $scope): self
    {
        $this->withScopes = \is_string($scope) ? [$scope] : $scope;
        collect($this->withScopes)->each(fn ($scope) => $this->validateScope($scope));

        return $this;
    }

    public function hasQueryScopes(): bool
    {
        return ! empty($this->withScopes) || ! empty(data_get($this->scopes, $this->context, ''));
    }

    public function queryScopes(Builder $query, $attributes): Builder
    {
        collect($this->withScopes)->each(function ($scope) use (&$query, $attributes) {
            $query = $query->{$scope}($attributes);
        });
        if (! empty(data_get($this->scopes, $this->context, ''))) {
            $scope = data_get($this->scopes, $this->context);
            $query = $query->{$scope}($attributes);
        }

        return $query;
    }

    protected function validateScope(string $scope): void
    {
        if (! $this->flexModel->hasNamedScope($scope)) {
            throw new \Exception("Scope {$scope} does not exist on the model");
        }
    }
}
