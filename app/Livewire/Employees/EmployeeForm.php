<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On; 
use App\Services\EmployeeService;
use App\Models\Position;

class EmployeeForm extends Component
{
    #[Validate('required|string|max:50')]
    public $first_name = '';

    #[Validate('required|string|max:50')]
    public $last_name = '';

    #[Validate('nullable|exists:positions,id')]
    public $position_id = '';

    public function save(EmployeeService $service)
    {
        $this->validate();

        try {
            $service->createEmployee([
                'first_name'  => $this->first_name,
                'last_name'   => $this->last_name,
                'position_id' => $this->position_id ?: null,
            ]);

            session()->flash('success', 'Pracownik został dodany.');
            
            return $this->redirectRoute('employees.index', navigate: true);

        } catch (\Exception $e) {
            $this->addError('base', 'Wystąpił błąd: ' . $e->getMessage());
        }
    }

    #[On('reset-employee-form')]
    public function resetForm()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.employees.employee-form', [
            'positions' => Position::get()
        ]);
    }
}