<?php

namespace Memphis\Dashboards\Panels;

use App\Models\StockMovement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class StockMovementsStats
{
    public static function entriesSeries()
    {
        return StockMovement::query()
            ->entries()
            ->whereBetween('movement_date',
                [self::start(), self::end()])
            ->selectRaw('DATE(movement_date) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');
    }

    public static function exits(): Collection
    {
        return StockMovement::query()
            ->exits()
            ->whereBetween('movement_date',
                [self::start(), self::end()])
            ->selectRaw('DATE(movement_date) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');
    }

    public static function exitsSeries(): Collection
    {
        return self::days()
            ->map(fn ($d) => (int) (self::exits()[$d] ?? 0));
    }
    public static function getCardLabels(): Collection
    {
        return self::days()
            ->map(fn ($d) => (int) (self::entriesSeries()[$d] ?? 0));
    }

    private static function days(): Collection
    {
        return collect(
            range(0, 29)
        )
            ->map(fn ($i) => self::start()->copy()->addDays($i)
                ->format('Y-m-d'));
    }

    private static function start(): Carbon
    {
        return Carbon::now()->subDays(29)->startOfDay();
    }

    private static function end(): Carbon
    {
        return Carbon::now()->endOfDay();
    }
}
