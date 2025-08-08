<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pracownik extends Model
{
    protected $table = 'pracownicy';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'imie',
        'nazwisko'
    ];

    public function getImieAttribute($value)
    {
        return ucfirst($value);
    }

    public function getNazwiskoAttribute($value)
    {
        return ucfirst($value);
    }
}
