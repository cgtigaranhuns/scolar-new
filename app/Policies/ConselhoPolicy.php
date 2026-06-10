<?php

namespace App\Policies;

use App\Models\Conselho;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ConselhoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
         return $user->hasPermissionTo('Ver Conselho');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Conselho $conselho): bool
    {
        return $user->hasPermissionTo('Ver Conselho');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Criar Conselho');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Conselho $conselho): bool
    {
        return $user->hasPermissionTo('Editar Conselho');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Conselho $conselho): bool
    {
        return $user->hasPermissionTo('Deletar Conselho');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Conselho $conselho): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Conselho $conselho): bool
    {
        return false;
    }
}
