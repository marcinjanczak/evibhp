<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On; 
use App\Services\EmployeeService;
use App\Models\Position;

class EmployeeForm extends Component
{
    public ?\App\Models\Employee $employee = null;

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
            if ($this->employee) {
                $service->updateEmployee($this->employee, [
                    'first_name'  => $this->first_name,
                    'last_name'   => $this->last_name,
                    'position_id' => $this->position_id ?: null,
                ]);
                $message = 'Dane pracownika zostały zaktualizowane.';
            } else {
                $service->createEmployee([
                    'first_name'  => $this->first_name,
                    'last_name'   => $this->last_name,
                    'position_id' => $this->position_id ?: null,
                ]);
                $message = 'Pracownik został dodany.';
            }

            session()->flash('success', $message);
            
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

    #[On('edit-employee')]
    public function editEmployee($id)
    {
        $this->employee = \App\Models\Employee::findOrFail($id);
        $this->first_name = $this->employee->first_name;
        $this->last_name = $this->employee->last_name;
        $this->position_id = $this->employee->position_id;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.employees.employee-form', [
            'positions' => Position::get()
        ]);
    }
}