<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Przedmiot extends Model
{
    protected $table = 'przedmiot';

    protected $primaryKey = 'IdPrzedmiot';
    public $timestamps = false;

    protected $fillable = [
        'Nazwa',
        'Typ',
        'Rozmiar',
    ];

    public function stan()
    {
        return $this->hasOne(StanPrzedmiotu::class, 'IdPrzedmiot');
    }
}
