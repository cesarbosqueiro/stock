<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitMeasurement extends Model
{
    protected $fillable = [
        'name',
        'abbreviation',
    ];

    /**
     * Produtos com esta unidade de medida
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
