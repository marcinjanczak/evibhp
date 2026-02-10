<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Issue extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $casts = [
        'issued_at' => 'datetime',
        'returned_at' => 'datetime', 
        'due_date' => 'date',
    ];

    public function scopeActive(Builder $query)
    {
        return $query->whereNull('returned_at');
    }

    public function scopeArchived(Builder $query)
    {
        return $query->whereNotNull('returned_at');
    }
    
    public function employee() { return $this->belongsTo(Employee::class); }
    public function batch() { return $this->belongsTo(Batch::class); }
}