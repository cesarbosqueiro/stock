<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    /**
     * Produto relacionado
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Adiciona quantidade ao estoque
     */
    public function addQuantity(float $quantity): void
    {
        $this->increment('quantity', $quantity);
    }

    /**
     * Remove quantidade do estoque
     */
    public function removeQuantity(float $quantity): void
    {
        $this->decrement('quantity', $quantity);
    }

    /**
     * Atualiza a quantidade para um valor específico
     */
    public function setQuantity(float $quantity): void
    {
        $this->update(['quantity' => $quantity]);
    }
}
