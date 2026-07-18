<?php

namespace App\Observers;

use App\Models\AdminTask;
use Illuminate\Support\Facades\Cache;

class AdminTaskObserver
{
    public function creating(AdminTask $adminTask): void
    {
        $adminTask->last_modified_by = auth()->id();
        $adminTask->last_modified_at = now();
    }

    public function updating(AdminTask $adminTask): void
    {
        $adminTask->last_modified_by = auth()->id();
        $adminTask->last_modified_at = now();
    }

    public function saved(AdminTask $adminTask): void
    {
        if ($adminTask->wasChanged('status')) {
            Cache::forget('dashboard_stats');
        }
    }

    public function deleted(AdminTask $adminTask): void
    {
        Cache::forget('dashboard_stats');
    }
}
