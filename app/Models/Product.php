<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage as FacadesStorage;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'type', 'preview_image_path'];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->batches()->sum('current_quantity');
    }

    public function getImageUrlAttribute()
    {
        if ($this->preview_image_path) {
            return FacadesStorage::url($this->preview_image_path);
        }

        return asset('images/default_item_image.png');
    }
}