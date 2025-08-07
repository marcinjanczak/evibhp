<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StanPrzedmiotow extends Model {
    protected $table = 'stanprzedmiotow';
    protected $primaryKey = 'IdStanPrzedmiotu';
    public function przedmiot(){
        return $this->belongsTo(Przedmiot::class, 'IdPrzedmiot', 'IdPrzedmiot');
    }
}
