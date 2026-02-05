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
    public function index(): View
    {
        $today = Carbon::now();
        $nextMonth = $today->copy()->addMonth();

        // Wydania, których termin zwrotu/wymiany mija w ciągu najbliższego miesiąca
        $expiringIssues = Issue::with(['employee', 'product'])
            ->whereBetween('due_date', [$today, $nextMonth])
            ->whereNull('returned_at') // Tylko te, które nie zostały jeszcze zwrócone
            ->orderBy('due_date')
            ->get();

        $issues = Issue::with(['employee', 'product'])->orderBy('issued_at', 'desc')->get();
        
        return view('issues.index', compact('issues', 'expiringIssues'));
    }

    public function create(): View
    {
        $employees = Employee::orderBy('last_name', 'asc')->get();
        $products = Product::with('inventory')->orderBy('type', 'asc')->get();

        return view('issues.create', compact('employees', 'products'));
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

        // Sprawdzenie stanu magazynowego
        if (!$product->inventory || $product->inventory->quantity < $validatedData['quantity']) {
            return back()->withInput()->with('error', 'Niewystarczająca ilość przedmiotu w magazynie.');
        }

        try {
            DB::beginTransaction();

            // Jeśli użytkownik nie podał daty zwrotu, bierzemy datę ważności z produktu
            $dueDate = $validatedData['due_date'] ?? $product->expiration_date;

            Issue::create([
                'employee_id' => $validatedData['employee_id'],
                'product_id' => $validatedData['product_id'],
                'quantity' => $validatedData['quantity'],
                'issued_at' => $validatedData['issued_at'],
                'due_date' => $dueDate,
            ]);

            // Odejmowanie z inwentarza
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
        // Przy usuwaniu wydania, towar wraca na magazyn
        Inventory::where('product_id', $issue->product_id)
            ->increment('quantity', (int) $issue->quantity);
            
        $issue->delete();

        return redirect()->back()->with('success', 'Wydanie usunięte, towar wrócił na stan.');
    }
}