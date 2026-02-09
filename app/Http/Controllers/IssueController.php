<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Issue;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class IssueController extends Controller
{
    public function index()
    {


        $issues = \App\Models\Issue::with(['employee', 'batch.product'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

        $upcomingIssues = Issue::with(['employee', 'batch.product'])
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

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'issued_at' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issued_at',
        ]);

        $product = Product::with('inventory')->find($validatedData['product_id']);

        if (!$product->inventory || $product->inventory->quantity < $validatedData['quantity']) {
            return back()->withInput()->with('error', 'Niewystarczająca ilość przedmiotu w magazynie.');
        }

        try {
            DB::beginTransaction();

            $dueDate = $validatedData['due_date'] ?? $product->expiration_date;

            Issue::create([
                'employee_id' => $validatedData['employee_id'],
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
                'issued_at' => $validatedData['issued_at'],
                'due_date' => $dueDate,
            ]);

            $product->inventory->decrement('quantity', $validatedData['quantity']);

            DB::commit();

            return redirect()->route('issues.index')
                ->with('success', 'Wydanie zostało pomyślnie zarejestrowane.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Wystąpił błąd: ' . $e->getMessage());
        }
    }

    public function destroy(Issue $issue): RedirectResponse
    {
        Inventory::where('product_id', $issue->product_id)
            ->increment('quantity', (int) $issue->quantity);
            
        $issue->delete();

        return redirect()->back()->with('success', 'Wydanie usunięte, towar wrócił na stan.');
    }
}