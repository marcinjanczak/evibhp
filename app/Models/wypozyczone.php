<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pracownik;

class Wypozyczone extends Model
{
    public $incrementing = false;
    public $keyType = 'string';
    protected $primaryKey = 'IdWypozyczenia';
    protected $table = 'wypozyczone';
    public $timestamps = false;
    protected $casts = [
    'Data' => 'datetime',
];
    protected $fillable = [
        'IdPracownika',
        'IdPrzedmiot',
        'Ilosc',
        'Data',
        'DataDoZwrotu',
        'DataZwrotu',
    ];

    public function pracownik()
    {
        return $this->belongsTo(Pracownik::class, 'IdPracownika');
    }

    public function przedmiot()
    {
        return $this->belongsTo(Przedmiot::class, 'IdPrzedmiot');
    }
    public function getRouteKeyName()
{
    return 'IdWypozyczenia';
}

}
