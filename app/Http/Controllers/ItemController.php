<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ItemService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ItemController extends Controller
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(): View
    {
        $items = Product::with('batches')->orderBy('name', 'asc')->get();
        return view('items.index', compact('items'));
    }

    public function create(): View
    {
        return view('items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'size' => 'required|string|max:50',
            'preview_image' => 'nullable|image|max:5120',
            'batch_number' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1',
            'expiration_date' => 'required|date',
            'invoice_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        $this->itemService->createProductWithBatch($validated);

        return redirect()->route('items.index')->with('success', 'Produkt i pierwsza partia dodane.');
    }

    public function show(Product $item): View
    {
        $item->load('batches');
        
        $rentals = \App\Models\Issue::with('employee')
            ->whereIn('batch_id', $item->batches->pluck('id'))
            ->orderBy('issued_at', 'desc')
            ->get();

        return view('items.show', compact('item', 'rentals'));
    }

    public function edit(Product $item): View
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Product $item): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'size' => 'required|string|max:50',
            'preview_image' => 'nullable|image|max:5120',
        ]);

        $this->itemService->updateProduct($item, $validated);

        return redirect()->route('items.index')->with('success', 'Dane produktu zaktualizowane.');
    }

    public function destroy(Product $item): RedirectResponse
    {
        $this->itemService->deleteProduct($item);
        return redirect()->route('items.index')->with('success', 'Produkt usunięty.');
    }
}