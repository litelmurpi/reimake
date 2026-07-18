<?php

namespace App\Policies;

use App\Models\AdminTask;
use App\Models\User;

class AdminTaskPolicy
{
    public function viewAny(User $user): bool
    {
        // BPH can view all, others can view if they have permission
        return $user->hasPermissionTo('admin_task.read') || $user->hasRole('bph');
    }

    public function view(User $user, AdminTask $adminTask): bool
    {
        return $user->hasPermissionTo('admin_task.read') || $user->hasRole('bph');
    }

    public function create(User $user, AdminTask $adminTask = null): bool
    {
        if (!$adminTask) {
            return clone $user->hasRole('bph') || $user->hasPermissionTo('admin_task.create');
        }
        
        if ($user->hasRole('bph')) {
            // Wait, even BPH should probably only create if they are koordinator for that divisi?
            // PRD: BPH has full CRUD in BPH, Humas, Dekdok.
            return true;
        }

        return $user->isKoordinatorOf($adminTask->divisi);
    }

    public function update(User $user, AdminTask $adminTask): bool
    {
        if ($user->hasRole('bph')) return true;
        if ($user->isKoordinatorOf($adminTask->divisi)) return true;
        if ($user->belongsToDivisi($adminTask->divisi)) return true; // Anggota updating status
        return false;
    }

    public function delete(User $user, AdminTask $adminTask): bool
    {
        if ($user->hasRole('bph')) return true;
        return $user->isKoordinatorOf($adminTask->divisi);
    }
}
