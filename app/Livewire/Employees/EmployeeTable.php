<?php

namespace App\Livewire\Employees;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class EmployeeTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = ''; 

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $employees = Employee::query()
            ->with('position')
            ->when($this->search, function (Builder $query) {
                $query->where('last_name', 'like', "%{$this->search}%")
                      ->orWhere('first_name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
            })
            ->orderBy('last_name', 'asc')
            ->paginate(10);

        return view('livewire.employees.employee-table', [
            'employees' => $employees
        ]);
    }
}