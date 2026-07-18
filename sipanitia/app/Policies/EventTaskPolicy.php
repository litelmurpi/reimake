<?php

namespace App\Policies;

use App\Models\EventTask;
use App\Models\User;
use App\Models\Divisi;

class EventTaskPolicy
{
    private function getSieAcaraDivisi()
    {
        return Divisi::where('nama_divisi', 'Sie Acara')->first();
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('event_task.read') || $user->hasRole('bph');
    }

    public function view(User $user, EventTask $eventTask): bool
    {
        return $user->hasPermissionTo('event_task.read') || $user->hasRole('bph');
    }

    public function create(User $user): bool
    {
        // Actually, PRD says Sie Acara controls it. But in the model, they can just use permissions.
        return $user->hasPermissionTo('event_task.create');
    }

    public function update(User $user, EventTask $eventTask): bool
    {
        $sieAcara = $this->getSieAcaraDivisi();
        if (!$sieAcara) return false;

        if ($user->isKoordinatorOf($sieAcara)) return true;
        if ($user->belongsToDivisi($sieAcara)) return true;
        if ($user->hasRole('bph')) return true;

        return false;
    }

    public function delete(User $user, EventTask $eventTask): bool
    {
        $sieAcara = $this->getSieAcaraDivisi();
        if (!$sieAcara) return false;

        return $user->isKoordinatorOf($sieAcara) || $user->hasRole('bph');
    }
}
