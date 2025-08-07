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
    public function destroy($id){
        $employee = Pracownik::findOrFail($id);
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Pracownik został usunięty');
    }
}
