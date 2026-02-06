<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['name', 'type', 'preview_image_path'];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->batches()->sum('current_quantity');
    }
}