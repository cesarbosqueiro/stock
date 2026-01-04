<?php

declare(strict_types=1);

namespace Memphis\Dashboards\Panels;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Carbon;

class InventoryStatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '60s';

    #[\Override]
    protected function getCards(): array
    {
        $totalProducts = Product::query()->count();
        $activeProducts = Product::query()->active()->count();

        $lowStockProducts = Product::query()->lowStock()->count();

        $stockValues = ProductStock::query()
            ->select('product_stocks.product_id', 'product_stocks.quantity')
            ->get()
            ->map(function ($row) {
                $lastPrice = StockMovement::query()
                    ->where('product_id', $row->product_id)
                    ->whereIn('type',
                        [StockMovement::TYPE_ENTRY, StockMovement::TYPE_ADJUSTMENT])
                    ->whereNotNull('unit_price')
                    ->orderByDesc('movement_date')
                    ->value('unit_price');

                $price = $lastPrice ?? 0;

                return ($row->quantity ?? 0) * (float) $price;
            })->sum();

        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $movementsToday = StockMovement::query()->whereDate('movement_date', $today)->count();
        $movementsMonth = StockMovement::query()->whereBetween('movement_date', [$startOfMonth, Carbon::now()])->count();
        $saidasNoMes = StockMovement::query()->whereDate('movement_date', $startOfMonth)->count();

        return [
            BaseWidget\Stat::make('Produtos (ativos/total)', $activeProducts.' / '.$totalProducts)
                ->description('Produtos cadastrados e ativos')
                ->color('success')
                ->icon('heroicon-o-cube'),

            BaseWidget\Stat::make('Produtos com estoque baixo', (string) $lowStockProducts)
                ->description('Abaixo do mínimo definido')
                ->color($lowStockProducts > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-exclamation-triangle'),

            BaseWidget\Stat::make('Valor total em estoque', 'R$ '.number_format($stockValues, 2, ',', '.'))
                ->description('Estimado pelo último preço conhecido')
                ->color('primary')
                ->icon('heroicon-o-banknotes'),

            BaseWidget\Stat::make('Movimentações hoje', (string) $movementsToday)
                ->description('Entradas, saídas e ajustes do dia')
                ->color('info')
                ->icon('heroicon-o-arrow-path'),

            BaseWidget\Stat::make('Movimentações no mês', (string) $movementsMonth)
                ->description('Período: mês atual')
                ->color('info')
                ->icon('heroicon-o-calendar-days'),
            BaseWidget\Stat::make('Valor total de saidas', (string) $saidasNoMes)
                ->description('Período: mês atual')
                ->color('info')
                ->icon('heroicon-o-calendar-days'),
        ];
    }
}
