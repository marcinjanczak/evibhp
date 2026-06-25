<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use App\Models\Employee;
use App\Models\Position;

class AssignPosition extends Component
{
    public Employee $employee;
    public $search = '';
    public $selectedPositionId = null;

    public function mount(Employee $employee)
    {
        $this->employee = $employee;
    }

    public function selectPosition($id)
    {
        $this->selectedPositionId = $id;
    }

    public function updatedSearch()
    {
        $this->selectedPositionId = null;
    }

    public function assignPosition()
    {
        if (!$this->selectedPositionId) {
            return;
        }

        $this->employee->position_id = $this->selectedPositionId;
        $this->employee->save();
        $this->employee->refresh();

        $this->selectedPositionId = null;
        $this->search = '';

        session()->flash('success', 'Stanowisko zostało przypisane.');
        $this->dispatch('close-modal');
    }

    public function removePosition()
    {
        $this->employee->position_id = null;
        $this->employee->save();
        $this->employee->refresh();
        session()->flash('success', 'Stanowisko zostało usunięte.');
    }

    public function render()
    {
        $query = Position::query();

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $positions = $query->orderBy('name')->limit(5)->get();

        return view('livewire.employees.assign-position', [
            'positions' => $positions
        ]);
    }
}
