<?php

namespace App\Policies;

use App\Models\Acompanhamento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AcompanhamentoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
       return $user->hasPermissionTo('Ver Acompanhamento');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Acompanhamento $acompanhamento): bool
    {
        return $user->hasPermissionTo('Ver Acompanhamento');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('Criar Acompanhamento');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Acompanhamento $acompanhamento): bool
    {
        return $user->hasPermissionTo('Editar Acompanhamento');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Acompanhamento $acompanhamento): bool
    {
        return $user->hasPermissionTo('Deletar Acompanhamento');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Acompanhamento $acompanhamento): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Acompanhamento $acompanhamento): bool
    {
        return false;
    }
}
