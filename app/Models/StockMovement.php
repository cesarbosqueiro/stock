<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'unit_price',
        'total_value',
        'reference',
        'notes',
        'movement_date',
        'user_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
        'movement_date' => 'date',
    ];

    /**
     * Tipos de movimentação
     */
    const string TYPE_ENTRY = 'entry';
    const string TYPE_EXIT = 'exit';
    const string TYPE_ADJUSTMENT = 'adjustment';

    /**
     * Produto relacionado
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Usuário que registrou a movimentação
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot do model para calcular total_value automaticamente
     */
    protected static function booted(): void
    {
        static::saving(function (StockMovement $movement) {
            if ($movement->unit_price && $movement->quantity) {
                $movement->total_value = abs($movement->quantity) * $movement->unit_price;
            }
        });

        static::created(function (StockMovement $movement) {
            $movement->updateProductStock();
        });
    }

    /**
     * Atualiza o estoque do produto após movimentação
     */
    public function updateProductStock(): void
    {
        $stock = ProductStock::firstOrCreate(
            ['product_id' => $this->product_id],
            ['quantity' => 0]
        );

        match($this->type) {
            self::TYPE_ENTRY => $stock->addQuantity($this->quantity),
            self::TYPE_EXIT => $stock->removeQuantity(abs($this->quantity)),
            self::TYPE_ADJUSTMENT => $stock->setQuantity($this->quantity),
        };
    }

    /**
     * Scope para entradas
     */
    public function scopeEntries(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_ENTRY);
    }

    /**
     * Scope para saídas
     */
    public function scopeExits(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_EXIT);
    }

    /**
     * Scope para ajustes
     */
    public function scopeAdjustments(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_ADJUSTMENT);
    }

    /**
     * Scope por período
     */
    public function scopeBetweenDates(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('movement_date', [$startDate, $endDate]);
    }
}
