<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Issue extends Model
{
    protected $fillable = [
        'employee_id',
        'batch_id',
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

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function product()
    {
        return $this->batch->product();
    }
}