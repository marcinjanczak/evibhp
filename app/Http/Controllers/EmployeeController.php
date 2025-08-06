<?php

namespace App\Http\Controllers;

use App\Models\Pracownik;
use Illuminate\Http\Request;

class EmployeeController
{
    public function index(){
        $employees = Pracownik::all();

        return(view('employees.index', compact('employees')));
    }
}
