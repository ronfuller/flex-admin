<?php

namespace Psi\FlexAdmin\Fields;

use Illuminate\Support\Str;

trait FieldPermissions
{
    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function withoutPermissions(): self
    {
        $this->withPermissions = false;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function indexPermission(string $permission): self
    {
        $this->permissions['index'] = $permission;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function detailPermission(string $permission): self
    {
        $this->permissions['detail'] = $permission;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function createPermission(string $permission): self
    {
        $this->permissions['create'] = $permission;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function editPermission(string $permission): self
    {
        $this->permissions['edit'] = $permission;

        return $this;
    }

    /**
     * @return \Psi\FlexAdmin\Fields\Field
     */
    public function withPermissions(string $context, mixed $model): self
    {
        $this->attributes['enabled'] = $this->authorizeEnabledContext($context, $model);

        return $this;
    }

    private function authorizeEnabledContext(string $context, mixed $model): bool
    {
        $permission = Str::of($this->permissions[$context])->contains('{entity}') ?
            $model->qualifyColumn((string) Str::of($this->permissions[$context])->replace("{entity}.", "")) : $this->permissions[$context];

        return $this->withPermissions ? (auth()->check() ? auth()->user()->can($permission) : true) : true;
    }

    protected function setDefaultPermissions()
    {
        $this->permissions = collect(self::CONTEXTS)->mapWithKeys(fn ($context) => [$context => self::CONTEXT_PERMISSIONS[$context]])->all();
    }
}
