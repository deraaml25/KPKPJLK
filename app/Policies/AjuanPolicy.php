<?php

namespace App\Policies;

use App\Models\Ajuan;
use App\Models\User;

class AjuanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // tenant scope already isolates this
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ajuan $ajuan): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->desa_id === $ajuan->desa_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin Dinpermasdes (super_admin) TIDAK BOLEH berinisiatif membuat draf desa!
        return $user->role === 'desa';
    }

    /**
     * Determine whether the user can update the model (Immutable Actions rule).
     */
    public function update(User $user, Ajuan $ajuan): bool
    {
        if ($user->role !== 'desa' || $user->desa_id !== $ajuan->desa_id) {
            return false;
        }

        // Aturan Kunci Berkas: hanya draft dan direvisi yang boleh diperbaiki
        return in_array($ajuan->status, ['draft', 'direvisi']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ajuan $ajuan): bool
    {
        if ($user->role !== 'desa' || $user->desa_id !== $ajuan->desa_id) {
            return false;
        }

        return in_array($ajuan->status, ['draft']); // Cuma draft yang bisa dihapus
    }
}
