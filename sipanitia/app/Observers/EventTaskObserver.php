<?php

namespace App\Observers;

use App\Models\EventTask;
use Illuminate\Support\Facades\Cache;

class EventTaskObserver
{
    public function creating(EventTask $eventTask): void
    {
        $eventTask->last_modified_by = auth()->id();
        $eventTask->last_modified_at = now();
    }

    public function updating(EventTask $eventTask): void
    {
        $eventTask->last_modified_by = auth()->id();
        $eventTask->last_modified_at = now();
    }

    public function saved(EventTask $eventTask): void
    {
        if ($eventTask->wasChanged('status')) {
            Cache::forget('dashboard_stats');
        }
    }

    public function deleted(EventTask $eventTask): void
    {
        Cache::forget('dashboard_stats');
    }
}
