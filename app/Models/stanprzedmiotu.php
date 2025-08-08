<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StanPrzedmiotu extends Model
{
    protected $table = 'stanprzedmiotow';

    protected $primaryKey = 'IdPrzedmiot';
    public $timestamps = false;

    public function przedmiot()
    {
        return $this->belongsTo(Przedmiot::class, 'IdPrzedmiot');
    }
}