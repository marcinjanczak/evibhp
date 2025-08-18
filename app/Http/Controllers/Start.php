<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Start extends Controller
{
    public function index(){
        return view('start');
    }
}
