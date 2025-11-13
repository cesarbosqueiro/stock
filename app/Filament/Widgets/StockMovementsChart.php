<?php

namespace App\Filament\Widgets;

use App\Models\StockMovement;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class StockMovementsChart extends ChartWidget
{
    protected ?string $heading = 'Movimentações (últimos 30 dias)';

    protected ?string $pollingInterval = '60s';

    protected function getData(): array
    {
        $start = Carbon::now()->subDays(29)->startOfDay();
        $end = Carbon::now()->endOfDay();

        $days = collect(range(0, 29))->map(fn ($i) => $start->copy()->addDays($i)->format('Y-m-d'));

        $entries = StockMovement::query()
            ->entries()
            ->whereBetween('movement_date', [$start, $end])
            ->selectRaw('DATE(movement_date) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');

        $exits = StockMovement::query()
            ->exits()
            ->whereBetween('movement_date', [$start, $end])
            ->selectRaw('DATE(movement_date) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');

        $labels = $days->map(fn ($d) => Carbon::parse($d)->format('d/m'));
        $entrySeries = $days->map(fn ($d) => (int) ($entries[$d] ?? 0));
        $exitSeries = $days->map(fn ($d) => (int) ($exits[$d] ?? 0));

        return [
            'datasets' => [
                [
                    'label' => 'Entradas',
                    'data' => $entrySeries,
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22,163,74,0.2)',
                ],
                [
                    'label' => 'Saídas',
                    'data' => $exitSeries,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239,68,68,0.2)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
