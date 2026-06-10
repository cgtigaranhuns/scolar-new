<?php

namespace App\Policies;

use App\Models\AreaConhecimento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AreaConhecimentoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('Ver Area Conhecimento');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AreaConhecimento $areaConhecimento): bool
    {
        return $user->hasPermissionTo('Ver Area Conhecimento');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Criar Area Conhecimento');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AreaConhecimento $areaConhecimento): bool
    {
        return $user->hasPermissionTo('Editar Area Conhecimento');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AreaConhecimento $areaConhecimento): bool
    {
        return $user->hasPermissionTo('Deletar Area Conhecimento');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AreaConhecimento $areaConhecimento): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AreaConhecimento $areaConhecimento): bool
    {
        return false;
    }
}
