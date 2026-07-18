<?php

namespace App\Observers;

use App\Models\InventoryItem;
use Illuminate\Support\Facades\Cache;

class InventoryItemObserver
{
    public function creating(InventoryItem $inventoryItem): void
    {
        $inventoryItem->last_modified_by = auth()->id();
        $inventoryItem->last_modified_at = now();
    }

    public function updating(InventoryItem $inventoryItem): void
    {
        $inventoryItem->last_modified_by = auth()->id();
        $inventoryItem->last_modified_at = now();
    }

    public function saved(InventoryItem $inventoryItem): void
    {
        Cache::forget('dashboard_stats');
    }

    public function deleted(InventoryItem $inventoryItem): void
    {
        Cache::forget('dashboard_stats');
    }
}
