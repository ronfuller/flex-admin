<?php

namespace Psi\FlexAdmin\Tests\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Psi\FlexAdmin\Tests\Models\Property;
use Psi\FlexAdmin\Tests\Models\User;

class PropertyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->permissions?->contains('properties.view-any');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @param  \Psi\FlexAdmin\Tests\Models\Property  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return $user->permissions?->contains('properties.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->permissions?->contains('properties.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $user->permissions?->contains('properties.edit');
    }

    /**
     * Determine whether the user can admin the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function admin(User $user)
    {
        return $user->permissions?->contains('properties.admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @param  \Psi\FlexAdmin\Tests\Models\Property  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $user->permissions?->contains('properties.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @param  \Psi\FlexAdmin\Tests\Models\Property  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Property $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @param  \Psi\FlexAdmin\Tests\Models\Property  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Property $model)
    {
        //
    }
}
