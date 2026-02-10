<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeController extends Controller
{
    public function index(): View
    {
        // Zmieniamy 'nazwisko' na 'last_name'
        $employees = Employee::orderBy('last_name', 'asc')->get();
        return view('employees.index', compact('employees'));
    }

    public function create(): View
    {
        return view('employees.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // Walidacja angielskich nazw pól
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'position_id' => 'nullable|exists:positions,id', // jeśli masz już stanowiska
        ]);

        Employee::create($validatedData);

        return redirect()->route('employees.index')
            ->with('success', 'Pracownik został pomyślnie dodany');
    }

    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
        ]);

        $employee->update($validatedData);

        return redirect()->route('employees.index')
            ->with('success', 'Dane pracownika zostały zaktualizowane');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();
        return redirect()->route('employees.index')
            ->with('success', 'Pracownik został usunięty');
    }

    public function show(Employee $employee): View
    {

        $issues = $employee->issues()
                    ->active()
                    ->with(['batch.product']) 
                    ->orderBy('due_date', 'asc')
                    ->get();

        return view('employees.show', compact('employee', 'issues'));
    }
}