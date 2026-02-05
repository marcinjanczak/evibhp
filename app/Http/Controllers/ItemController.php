<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Issue; // Zamiast Wypozyczenie
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ItemController extends Controller
{
    public function index(): View
    {
        // Zmieniamy stanMagazynu -> inventory, nazwa -> name, typ -> type
        $items = Product::with('inventory')
            ->orderBy('type', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return view('items.index', compact('items'));
    }

    public function create(): View
    {
        return view('items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'size' => 'required|string|max:50',
            'quantity_added' => 'required|integer',
            'expiration_date' => 'nullable|date|after:today',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'invoice_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        // Obsługa zdjęcia
        if ($request->hasFile('preview_image')) {
            $path = $request->file('preview_image')->store('previews', 'public');
            $validatedData['preview_image_path'] = $path;
        }

        // Obsługa PDF
        if ($request->hasFile('invoice_pdf')) {
            $path = $request->file('invoice_pdf')->store('invoices', 'public');
            $validatedData['invoice_pdf_path'] = $path;
        }

        $product = Product::create($validatedData);

        // Tworzymy stan magazynowy (Inventory) od razu po dodaniu produktu
        Inventory::create([
            'product_id' => $product->id,
            'quantity' => $validatedData['quantity_added'],
        ]);

        return redirect()->route('items.index')->with('success', 'Przedmiot został dodany.');
    }

    public function edit(Product $item): View
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Product $item): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'type' => 'required|string|max:50',
            'size' => 'required|string|max:50',
            'expiration_date' => 'nullable|date|after:today',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'invoice_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('preview_image')) {
            if ($item->preview_image_path) {
                Storage::disk('public')->delete($item->preview_image_path);
            }
            $validatedData['preview_image_path'] = $request->file('preview_image')->store('previews', 'public');
        }

        if ($request->hasFile('invoice_pdf')) {
            if ($item->invoice_pdf_path) {
                Storage::disk('public')->delete($item->invoice_pdf_path);
            }
            $validatedData['invoice_pdf_path'] = $request->file('invoice_pdf')->store('invoices', 'public');
        }

        $item->update($validatedData);

        return redirect()->route('items.index')->with('success', 'Przedmiot został zaktualizowany.');
    }

    public function destroy(Product $item): RedirectResponse
    {
        // Usuwamy pliki z dysku przed usunięciem rekordu
        if ($item->preview_image_path) Storage::disk('public')->delete($item->preview_image_path);
        if ($item->invoice_pdf_path) Storage::disk('public')->delete($item->invoice_pdf_path);

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Produkt usunięty.');
    }

    public function show(Product $item): View
    {
        // Ładujemy wydania tego przedmiotu wraz z pracownikami
        $rentals = Issue::with('employee')
            ->where('product_id', $item->id)
            ->get();

        return view('items.show', compact('item', 'rentals'));
    }
}