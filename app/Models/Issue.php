<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends Model
{
    protected $fillable = [
        'employee_id',
        'product_id',
        'quantity',
        'issued_at',
        'due_date',
        'returned_at',
    ];

    // Automatyczna obsługa dat przez Carbon
    protected $casts = [
        'issued_at' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}