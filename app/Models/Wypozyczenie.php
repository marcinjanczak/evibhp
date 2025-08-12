<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wypozyczenie extends Model
{
    protected $table = 'wypozyczenia';
    protected $fillable = [
        'IdPracownika',
        'IdPrzedmiot',
        'Ilosc',
        'DataWypozyczenia',
        'DataPlanowenegoZwrotu',
        'DataRzeczywistegoZwrotu',
    ];
    public function pracownik(){
        return $this->belongsTo(Pracownik::class, 'IdPracownika');
    }
    public function przedmiot(){
        return $this->belongsTo(Przedmiot::class, 'IdPrzedmiot');
    }
}
