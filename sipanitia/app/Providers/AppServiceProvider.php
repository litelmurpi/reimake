<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\ConsumptionItem::observe(\App\Observers\ConsumptionItemObserver::class);
        \App\Models\AdminTask::observe(\App\Observers\AdminTaskObserver::class);
        \App\Models\EventTask::observe(\App\Observers\EventTaskObserver::class);
        \App\Models\InventoryItem::observe(\App\Observers\InventoryItemObserver::class);

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
