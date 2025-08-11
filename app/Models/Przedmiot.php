<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Przedmiot extends Model
{
    protected $table = 'przedmiot';
    protected $fillable = [
        'nazwa',
        'typ',
        'rozmiar',
        'ilosc_dodanych',
        'faktura_pdf_path',
        'zdjecie_pogladowe_path',
        'data_waznosci',
    ];
    public function stanMagazynu(): HasOne
    {
        return $this->hasOne(StanMagazynu::class, 'IdPrzedmiot');
    }
}
