<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends Model
{
    protected $fillable = [
        'employee_id',
        'batch_id', // Zmienione z product_id
        'quantity',
        'issued_at',
        'due_date',
        'returned_at',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'due_date' => 'date',
        'returned_at' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Wydanie teraz przypisane jest do konkretnej partii
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Opcjonalnie: Jeśli nadal chcesz mieć szybki dostęp do danych produktu 
     * (np. nazwy) przez wydanie, możesz dodać taką relację "przez partię"
     */
    public function product()
    {
        // Wydanie -> ma partię -> która ma produkt
        return $this->batch->product();
    }
}