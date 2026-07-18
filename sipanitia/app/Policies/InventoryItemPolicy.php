<?php

namespace App\Policies;

use App\Models\InventoryItem;
use App\Models\User;
use App\Models\Divisi;

class InventoryItemPolicy
{
    private function getSiePerkapDivisi()
    {
        return Divisi::where('nama_divisi', 'Sie Perkap')->first();
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('inventory.read') || $user->hasRole('bph');
    }

    public function view(User $user, InventoryItem $inventoryItem): bool
    {
        return $user->hasPermissionTo('inventory.read') || $user->hasRole('bph');
    }

    public function create(User $user): bool
    {
        $siePerkap = $this->getSiePerkapDivisi();
        if (!$siePerkap) return false;
        
        return $user->isKoordinatorOf($siePerkap) || $user->hasRole('bph');
    }

    public function update(User $user, InventoryItem $inventoryItem): bool
    {
        $siePerkap = $this->getSiePerkapDivisi();
        if (!$siePerkap) return false;

        if ($user->isKoordinatorOf($siePerkap)) return true;
        if ($user->belongsToDivisi($siePerkap)) return true;
        if ($user->hasRole('bph')) return true;

        return false;
    }

    public function delete(User $user, InventoryItem $inventoryItem): bool
    {
        $siePerkap = $this->getSiePerkapDivisi();
        if (!$siePerkap) return false;

        return $user->isKoordinatorOf($siePerkap) || $user->hasRole('bph');
    }
}
