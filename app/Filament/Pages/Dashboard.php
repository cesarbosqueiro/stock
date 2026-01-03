<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Memphis\Dashboards\Panels\InventoryStatsOverview;
use Memphis\Dashboards\Panels\RecentStockMovementsTable;
use Memphis\Dashboards\Panels\StockMovementsChart;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Inicio';

    #[\Override]
    public function getWidgets(): array
    {
        return [
            InventoryStatsOverview::class,
            StockMovementsChart::class,
            RecentStockMovementsTable::class,
        ];
    }
}
