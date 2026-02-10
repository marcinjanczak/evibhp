<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Issue;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use App\Services\IssueService;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::active()
                    ->with(['employee', 'batch.product'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        $upcomingIssues = Issue::active()
                    ->with(['employee', 'batch.product'])
                    ->whereDate('due_date', '>=', Carbon::now()) 
                    ->whereDate('due_date', '<=', Carbon::now()->addDays(30)) 
                    ->orderBy('due_date', 'asc') 
                    ->get();

        return view('issues.index', compact('issues', 'upcomingIssues'));
    }

    public function create()
    {
        return view('issues.create');
    }

    public function archive(Issue $issue, IssueService $service)
    {
        try {
            $service->archiveIssue($issue);
            return back()->with('success', 'Przedmiot został zarchiwizowany.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}