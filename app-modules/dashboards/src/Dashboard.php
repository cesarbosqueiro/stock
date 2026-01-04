<?php

namespace Memphis\Dashboards;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use Memphis\Dashboards\Panels\InventoryStatsOverview;
use Memphis\Dashboards\Panels\RecentStockMovementsTable;
use Memphis\Dashboards\Panels\StockMovementsChart;

final class Dashboard extends BaseDashboard
{
    #[\Override]
    public function getWidgets(): array
    {
        return [
            InventoryStatsOverview::class,
            StockMovementsChart::class,
            RecentStockMovementsTable::class,
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('dashboard::dashboard.title');
    }
}
