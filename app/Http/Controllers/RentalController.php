<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RentalController
{
    public function index()
    {
        return view('rentals.index');
    }
}
