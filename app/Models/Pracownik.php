<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pracownik extends Model
{
    protected $table = 'pracownicy';

        protected $fillable = [
        'imie', 
        'nazwisko'
    ];
}
