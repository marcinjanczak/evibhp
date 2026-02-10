<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'position_id', 
    ];

    public function issues(): HasMany
    {
        return $this->hasMany(Issue::class);
    }
    
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

}