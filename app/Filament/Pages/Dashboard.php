<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\InventoryStatsOverview;
use App\Filament\Widgets\RecentStockMovementsTable;
use App\Filament\Widgets\StockMovementsChart;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Inicio';

    public function getWidgets(): array
    {
        return [
            InventoryStatsOverview::class,
            StockMovementsChart::class,
            RecentStockMovementsTable::class,
        ];
    }
}
