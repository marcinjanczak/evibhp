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

    #[On('refresh-issues-table')] 
    public function refresh() 
    {
    }

    public function render()
    {
        $activeIssues = Issue::active()
            ->with(['employee', 'batch.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $archivedIssues = Issue::archived()
            ->with(['employee', 'batch.product'])
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
}