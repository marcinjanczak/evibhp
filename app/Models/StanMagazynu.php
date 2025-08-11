<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StanMagazynu extends Model
{
    //
    protected $table = 'stan_magazynu';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'IdPrzedmiot',
        'Ilosc',
    ];

    public function przedmiot(){
        return $this->belongsTo(Przedmiot::class, 'IdPrzedmiot');
    }
}
