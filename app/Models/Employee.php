<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    // Nie musisz już wpisywać $table, bo Laravel sam domyśli się 'employees'
    protected $fillable = [
        'first_name',
        'last_name',
        'position_id', // od razu przygotujmy pod stanowisko
    ];

    // public function position(): BelongsTo
    // {
    //     return $this->belongsTo(Position::class);
    // }

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
}