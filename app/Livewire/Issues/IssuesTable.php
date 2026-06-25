<?php

namespace App\Livewire\Issues;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On; 
use App\Models\Issue;
use App\Services\IssueService; 

class IssuesTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; 

    public $searchEmployee = '';
    public $searchProduct = '';
    public $searchDateFrom = '';
    public $searchDateTo = '';

    public function updating($field)
    {
        if (in_array($field, ['searchEmployee', 'searchProduct', 'searchDateFrom', 'searchDateTo'])) {
            $this->resetPage();
            $this->resetPage('historyPage');
        }
    }

    #[On('refresh-issues-table')] 
    public function refresh() 
    {
        // Odświeżenie komponentu
    }

    public function render()
    {
        $queryFilter = function ($q) {
            if ($this->searchEmployee) {
                $q->whereHas('employee', function ($q2) {
                    $q2->where('last_name', 'like', '%' . $this->searchEmployee . '%')
                       ->orWhere('first_name', 'like', '%' . $this->searchEmployee . '%');
                });
            }
            if ($this->searchProduct) {
                $q->whereHas('batch.product', function ($q2) {
                    $q2->where('name', 'like', '%' . $this->searchProduct . '%');
                });
            }
            if ($this->searchDateFrom) {
                $q->where('issued_at', '>=', $this->searchDateFrom);
            }
            if ($this->searchDateTo) {
                $q->where('issued_at', '<=', $this->searchDateTo . ' 23:59:59');
            }
        };

        $activeIssues = Issue::active()
            ->with(['employee.position', 'batch.product'])
            ->where($queryFilter)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $archivedIssues = Issue::archived()
            ->with(['employee', 'batch.product'])
            ->where($queryFilter)
            ->orderBy('returned_at', 'desc') 
            ->paginate(10, ['*'], 'historyPage'); 

        return view('livewire.issues.issues-table', [
            'activeIssues' => $activeIssues,
            'archivedIssues' => $archivedIssues
        ]);
    }

    public function archiveIssue($issueId, IssueService $service)
    {
        try {
            $issue = Issue::findOrFail($issueId);

            $service->archiveIssue($issue);

            session()->flash('success', 'Przedmiot został pomyślnie zarchiwizowany.');

        } catch (\Exception $e) {
            session()->flash('error', 'Wystąpił błąd: ' . $e->getMessage());
        }
    }


    public function updatedHistoryPage()
    {
        $this->dispatch('scroll-to-archive');
    }
}