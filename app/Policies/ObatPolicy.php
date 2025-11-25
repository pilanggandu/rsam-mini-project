<?php

namespace App\Policies;

use App\Models\Obat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ObatPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['doctor', 'pharmacist']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Obat $obat): bool
    {
        return in_array($user->role, ['doctor', 'pharmacist']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'pharmacist';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Obat $obat): bool
    {
        return $user->role === 'pharmacist';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Obat $obat): bool
    {
        return $user->role === 'pharmacist';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Obat $obat): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Obat $obat): bool
    {
        return false;
    }
}
