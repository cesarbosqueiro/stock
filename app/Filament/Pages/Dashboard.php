<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Visão Geral do Estoque';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\InventoryStatsOverview::class,
            \App\Filament\Widgets\StockMovementsChart::class,
            \App\Filament\Widgets\RecentStockMovementsTable::class,
        ];
    }
}
