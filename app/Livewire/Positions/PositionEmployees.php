<?php

namespace App\Livewire\Positions;

use Livewire\Component;
use App\Models\Position;
use App\Models\Employee;

class PositionEmployees extends Component
{
    public Position $position;
    public $selectedEmployee = '';
    public $search = '';

    public function selectEmployee($id)
    {
        $this->selectedEmployee = $id;
    }

    public function updatedSearch()
    {
        $this->selectedEmployee = '';
    }

    public function mount(Position $position)
    {
        $this->position = $position;
    }

    public function addEmployee()
    {
        $this->validate([
            'selectedEmployee' => 'required|exists:employees,id',
        ]);

        $employee = Employee::find($this->selectedEmployee);
        $employee->position_id = $this->position->id;
        $employee->save();

        $this->selectedEmployee = '';
        $this->position->load('employees'); // refresh
        
        session()->flash('success', 'Pracownik został dodany do stanowiska.');
        
        $this->dispatch('close-modal');
    }

    public function removeEmployee($employeeId)
    {
        $employee = Employee::find($employeeId);
        if ($employee && $employee->position_id === $this->position->id) {
            $employee->position_id = null;
            $employee->save();
            $this->position->load('employees'); // refresh
            session()->flash('success', 'Pracownik został usunięty ze stanowiska.');
        }
    }

    public function render()
    {
        $query = Employee::where(function ($q) {
            $q->where('position_id', '!=', $this->position->id)
              ->orWhereNull('position_id');
        });

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        $availableEmployees = $query->orderBy('last_name')
                                    ->limit(5)
                                    ->get();

        return view('livewire.positions.position-employees', [
            'availableEmployees' => $availableEmployees
        ]);
    }
}
