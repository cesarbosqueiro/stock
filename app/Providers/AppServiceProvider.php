<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Memphis\Dashboards\Panels\InventoryStatsOverview;
use Memphis\Dashboards\Panels\RecentStockMovementsTable;
use Memphis\Dashboards\Panels\StockMovementsChart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[\Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('memphis.dashboards.panels.stock-movements-chart', StockMovementsChart::class);
        Livewire::component('memphis.dashboards.panels.inventory-stats-overview', InventoryStatsOverview::class);
        Livewire::component('memphis.dashboards.panels.recent-stock-movements-table', RecentStockMovementsTable::class);
    }
}
