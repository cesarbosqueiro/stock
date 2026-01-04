<?php

namespace Memphis\Dashboards\Panels\Filament;

use Filament\Widgets\ChartWidget;
use Memphis\Dashboards\Panels\StockMovementsStats;

class StockMovementsCard extends ChartWidget
{
    protected ?string $heading = 'Movimentações (últimos 30 dias)';

    protected ?string $pollingInterval = '60s';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Entradas',
                    'data' => StockMovementsStats::entriesSeries(),
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22,163,74,0.2)',
                ],
                [
                    'label' => 'Saídas',
                    'data' => StockMovementsStats::exitsSeries(),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239,68,68,0.2)',
                ],
            ],
            'labels' => StockMovementsStats::getCardLabels(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
