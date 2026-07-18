<?php

namespace App\Policies;

use App\Models\ConsumptionItem;
use App\Models\User;
use App\Models\Divisi;

class ConsumptionItemPolicy
{
    private function getSieKonsumsiDivisi()
    {
        return Divisi::where('nama_divisi', 'Sie Konsumsi')->first();
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('consumption.read') || $user->hasRole('bph');
    }

    public function view(User $user, ConsumptionItem $consumptionItem): bool
    {
        return $user->hasPermissionTo('consumption.read') || $user->hasRole('bph');
    }

    public function create(User $user): bool
    {
        $sieKonsumsi = $this->getSieKonsumsiDivisi();
        if (!$sieKonsumsi) return false;
        
        return $user->isKoordinatorOf($sieKonsumsi) || $user->hasRole('bph');
    }

    public function update(User $user, ConsumptionItem $consumptionItem): bool
    {
        $sieKonsumsi = $this->getSieKonsumsiDivisi();
        if (!$sieKonsumsi) return false;

        if ($user->isKoordinatorOf($sieKonsumsi)) return true;
        if ($user->belongsToDivisi($sieKonsumsi)) return true;
        if ($user->hasRole('bph')) return true;

        return false;
    }

    public function delete(User $user, ConsumptionItem $consumptionItem): bool
    {
        $sieKonsumsi = $this->getSieKonsumsiDivisi();
        if (!$sieKonsumsi) return false;

        return $user->isKoordinatorOf($sieKonsumsi) || $user->hasRole('bph');
    }
}
