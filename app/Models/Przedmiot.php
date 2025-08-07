<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Przedmiot extends Model {
    protected $table = 'przedmiot';
    protected $fillable = [
        'nazwa',
        'typ',
        'rozmiar'
    ];
}
