<?php

namespace App\Http\Controllers;

use App\Models\Pracownik;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class EmployeeController
{
    public function index(){
        $employees = Pracownik::all();
        return(view('employees.index', compact('employees')));
    }
    public function edit(Pracownik $employee){
        return view('employees.edit', compact('employee'));
    }
    public function destroy($id){
        $employee = Pracownik::findOrFail($id);
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Pracownik został usunięty');
    }
    public function update(Request $request, Pracownik $employee){
        $validateData = $request->validate([
            'imie' => 'required|string|max:50',
            'nazwisko' => 'required|string|max:50',
            'imie' => 'required|string|max:50',
        ]);
        $employee->update($validateData);

        return redirect()->route('employees.index')->with('success', 'Dane pracownika zostały zaktualizowane');
    }
    
    public function create(){
        return view('employees.create');
    }
    public function store(Request $request){
            $validateData = $request->validate([
            'imie' => 'required|string|max:50',
            'nazwisko' => 'required|string|max:50',
            ]);
        Pracownik::create($validateData);
        return redirect()->route('employees.index')->with('success', 'Pracownik został pomyślnie dodany');
    }
}
