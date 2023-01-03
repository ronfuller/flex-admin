<?php

namespace Psi\FlexAdmin\Tests\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Psi\FlexAdmin\Tests\Models\Unit;
use Psi\FlexAdmin\Tests\Models\User;

class UnitPolicy
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
        return $user->permissions?->contains('units.view-any');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return $user->permissions?->contains('units.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->permissions?->contains('units.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $user->permissions?->contains('units.edit');
    }

    /**
     * Determine whether the user can admin the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function admin(User $user)
    {
        return $user->permissions?->contains('units.admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $user->permissions?->contains('units.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @param  \Psi\FlexAdmin\Tests\Models\Unit  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Unit $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Psi\FlexAdmin\Tests\Models\User  $user
     * @param  \Psi\FlexAdmin\Tests\Models\Unit  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Unit $model)
    {
        //
    }
}
