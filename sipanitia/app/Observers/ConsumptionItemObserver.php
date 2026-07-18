<?php

namespace App\Observers;

use App\Models\ConsumptionItem;
use Illuminate\Support\Facades\Cache;

class ConsumptionItemObserver
{
    public function creating(ConsumptionItem $consumptionItem): void
    {
        $consumptionItem->total_biaya = $consumptionItem->porsi_jumlah * $consumptionItem->harga_satuan;
    }

    public function updating(ConsumptionItem $consumptionItem): void
    {
        $consumptionItem->total_biaya = $consumptionItem->porsi_jumlah * $consumptionItem->harga_satuan;
    }

    public function saved(ConsumptionItem $consumptionItem): void
    {
        Cache::forget('dashboard_stats');
    }

    public function deleted(ConsumptionItem $consumptionItem): void
    {
        Cache::forget('dashboard_stats');
    }
}
