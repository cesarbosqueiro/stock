<?php

declare(strict_types=1);

namespace Memphis\Dashboards\Panels\Filament;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Memphis\Dashboards\Panels\InventoryStats;

class InventoryCard extends BaseWidget
{
    protected ?string $pollingInterval = '60s';

    #[\Override]
    protected function getCards(): array
    {
        return [
            BaseWidget\Stat::make('Produtos (ativos/total)',
                InventoryStats::getActiveProducts().' / '.InventoryStats::getTotalProducts(), )
                ->description('Produtos cadastrados e ativos')
                ->color('success')
                ->icon('heroicon-o-cube'),

            BaseWidget\Stat::make('Produtos com estoque baixo',
                (string) InventoryStats::getLowStockProducts())
                ->description('Abaixo do mínimo definido')
                ->color(InventoryStats::getLowStockProducts() > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-exclamation-triangle'),

            BaseWidget\Stat::make('Valor total em estoque', 'R$ '.number_format(InventoryStats::getTotalStocks(), 2, ',', '.'))
                ->description('Estimado pelo último preço conhecido')
                ->color('primary')
                ->icon('heroicon-o-banknotes'),

            BaseWidget\Stat::make('Movimentações hoje',
                (string) InventoryStats::getTodayMovements())
                ->description('Entradas, saídas e ajustes do dia')
                ->color('info')
                ->icon('heroicon-o-arrow-path'),

            BaseWidget\Stat::make('Movimentações no mês',
                (string) InventoryStats::getMonthMovements())
                ->description('Período: mês atual')
                ->color('info')
                ->icon('heroicon-o-calendar-days'),
            BaseWidget\Stat::make('Valor total de saidas',
                (string) InventoryStats::getMonthExits())
                ->description('Período: mês atual')
                ->color('info')
                ->icon('heroicon-o-calendar-days'),
        ];
    }
}
