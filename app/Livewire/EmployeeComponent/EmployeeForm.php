<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On; // Do czyszczenia formularza
use App\Services\EmployeeService;
use App\Models\Position; // Import modelu stanowisk

class EmployeeForm extends Component
{
    #[Validate('required|string|max:50')]
    public $first_name = '';

    #[Validate('required|string|max:50')]
    public $last_name = '';

    #[Validate('required|email|unique:employees,email')]
    public $email = '';

    #[Validate('nullable|exists:positions,id')] // Sprawdzamy czy ID istnieje w bazie
    public $position_id = ''; 

    // Metoda do zapisu
    public function save(EmployeeService $service)
    {
        $this->validate();

        try {
            $service->createEmployee([
                'first_name'  => $this->first_name,
                'last_name'   => $this->last_name,
                'email'       => $this->email,
                'position_id' => $this->position_id ?: null, // Jeśli pusty string to null
            ]);

            session()->flash('success', 'Pracownik dodany pomyślnie!');
            
            // Przeładowanie strony (zamknie modal i odświeży listę)
            return $this->redirectRoute('employees.index', navigate: true);

        } catch (\Exception $e) {
            $this->addError('base', 'Błąd: ' . $e->getMessage());
        }
    }

    // Metoda czyszcząca formularz po zamknięciu modala
    #[On('reset-employee-form')]
    public function resetForm()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        // Pobieramy wszystkie stanowiska do selecta
        return view('livewire.employee-form', [
            'positions' => Position::orderBy('name')->get()
        ]);
    }
}