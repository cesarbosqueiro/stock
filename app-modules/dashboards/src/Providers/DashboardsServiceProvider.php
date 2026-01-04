<?php

namespace Memphis\Dashboards\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Memphis\Dashboards\Panels\Filament\InventoryCard;
use Memphis\Dashboards\Panels\Filament\RecentStockMovementCard;
use Memphis\Dashboards\Panels\Filament\StockMovementsCard;

class DashboardsServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void {}

    public function boot(): void
    {
        Livewire::component('memphis.dashboards.panels.stock-movements-chart', StockMovementsCard::class);
        Livewire::component('memphis.dashboards.panels.inventory-stats-overview', InventoryCard::class);
        Livewire::component('memphis.dashboards.panels.recent-stock-movements-table', RecentStockMovementCard::class);
    }
}
