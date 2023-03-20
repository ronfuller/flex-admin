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
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->permissions?->contains('units.view-any');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return $user->permissions?->contains('units.view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->permissions?->contains('units.create');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $user->permissions?->contains('units.edit');
    }

    /**
     * Determine whether the user can admin the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function admin(User $user)
    {
        return $user->permissions?->contains('units.admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $user->permissions?->contains('units.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Unit $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Unit $model)
    {
        //
    }
}
