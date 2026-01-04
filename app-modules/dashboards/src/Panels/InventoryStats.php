<?php

declare(strict_types=1);

namespace Memphis\Dashboards\Panels;

use App\Models\Product;
use App\Models\ProductStock;
use App\Models\StockMovement;
use Illuminate\Support\Carbon;

class InventoryStats
{
    public static function getTotalProducts(): int
    {
        return Product::query()->count();
    }

    public static function getActiveProducts(): int
    {
        return Product::query()->active()->count();
    }

    public static function getLowStockProducts(): int
    {
        return Product::query()->lowStock()->count();
    }

    public static function getTotalStocks(): int|float
    {
        return ProductStock::query()
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
    }

    public static function getTodayMovements(): int
    {
        return StockMovement::query()
            ->whereDate('movement_date', Carbon::today())
            ->count();
    }

    public static function getMonthMovements(): int
    {
        return StockMovement::query()
            ->whereBetween('movement_date',
                [Carbon::now()->startOfMonth(), Carbon::now()]
            )
            ->count();
    }

    public static function getMonthExits(): int
    {
        return StockMovement::query()
            ->whereDate('movement_date', Carbon::now()->startOfMonth())
            ->count();
    }
}
