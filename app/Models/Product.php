<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'unit_measurement_id',
        'minimum_stock',
        'is_active',
    ];

    protected $casts = [
        'minimum_stock' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Categoria do produto
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Unidade de medida do produto
     */
    public function unitMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitMeasurement::class);
    }

    /**
     * Estoque atual do produto
     */
    public function stock(): HasOne
    {
        return $this->hasOne(ProductStock::class);
    }

    /**
     * Movimentações de estoque
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Accessor para obter quantidade em estoque
     */
    public function getCurrentStockAttribute(): float
    {
        return $this->stock?->quantity ?? 0;
    }

    /**
     * Verifica se o estoque está abaixo do mínimo
     */
    public function isBelowMinimumStock(): bool
    {
        return $this->current_stock < $this->minimum_stock;
    }

    /**
     * Scope para produtos ativos
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para produtos com estoque baixo
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereHas('stock', function ($q): void {
            $q->whereRaw('quantity < products.minimum_stock');
        });
    }
}
