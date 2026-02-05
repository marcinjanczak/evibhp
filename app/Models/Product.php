<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // W produkcie zostają tylko cechy stałe przedmiotu
    protected $fillable = [
        'name',
        'type',
        'size',
        'preview_image_path',
    ];

    // Relacja: Jeden produkt ma wiele partii (np. Kask biały z dostawy czerwiec i dostawy grudzień)
    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    // Accessor: pozwala wywołać $product->total_stock w widoku
    public function getTotalStockAttribute(): int
    {
        return $this->batches()->sum('current_quantity');
    }
}