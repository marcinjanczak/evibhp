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
    
    public $filter = 'all'; 

    public function updatedSearch() { $this->resetPage(); }
    public function updatedFilter() { $this->resetPage(); }

   public function render()
{
    $employees = Employee::query()
        ->with('position')
        
        ->withCount(['issues as active_issues_count' => function ($query) {
            $query->whereNull('returned_at');
        }])
        
        ->withExists(['issues as has_overdue_issues' => function ($query) {
            $query->whereNull('returned_at')
                  ->where('due_date', '<', now()->startOfDay());
        }])

        ->withExists(['issues as has_due_soon_issues' => function ($query) {
            $query->whereNull('returned_at')
                  ->where('due_date', '>=', now()->startOfDay())
                  ->where('due_date', '<=', now()->addDays(30));
        }])


        ->when($this->filter === 'has_items', function ($query) {
            $query->whereHas('issues', function ($q) {
                $q->whereNull('returned_at');
            })->orderByDesc('active_issues_count');
        })

        ->when($this->filter === 'overdue', function ($query) {
            $query->whereHas('issues', function ($q) {
                $q->whereNull('returned_at')
                  ->where('due_date', '<', now()->startOfDay());
            });
        })

        ->when($this->filter === 'due_soon', function ($query) {
            $query->whereHas('issues', function ($q) {
                $q->whereNull('returned_at')
                  ->where('due_date', '>=', now()->startOfDay())
                  ->where('due_date', '<=', now()->addDays(30));
            });
        })

        ->when($this->search, function (Builder $query) {
            $query->where(function($q) {
                $q->where('last_name', 'like', "%{$this->search}%")
                  ->orWhere('first_name', 'like', "%{$this->search}%");
            });
        })
        
        ->orderBy('last_name', 'asc')
        ->paginate(10);

    return view('livewire.employees.employee-table', [
        'employees' => $employees
    ]);
}
}