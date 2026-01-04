<?php

namespace Memphis\Dashboards;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Contracts\Support\Htmlable;
use Memphis\Dashboards\Panels\Filament\InventoryCard;
use Memphis\Dashboards\Panels\Filament\RecentStockMovementCard;
use Memphis\Dashboards\Panels\Filament\StockMovementsCard;
use Memphis\Dashboards\Panels\RecentStockMovementsTable;
use Memphis\Dashboards\Panels\StockMovementsStats;

final class Dashboard extends BaseDashboard
{
    #[\Override]
    public function getWidgets(): array
    {
        return [
            InventoryCard::class,
            StockMovementsCard::class,
            RecentStockMovementCard::class,
        ];
    }

    #[\Override]
    public function getTitle(): string|Htmlable
    {
        return __('dashboard::dashboard.title');
    }
}
