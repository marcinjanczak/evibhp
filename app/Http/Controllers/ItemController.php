<?php

namespace App\Http\Controllers;

use App\Models\Przedmiot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController
{
    public function index()
    {
        $items = Przedmiot::all();
        return view('items.index', compact('items'));
    }
    public function create()
    {
        return view('items.create');
    }
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'nazwa' => 'required|string|max:50',
            'typ' => 'required|string|max:50',
            'rozmiar' => 'required|string|max:50',
            'ilosc_dodanych' => 'required|int',
            'data_waznosci' => 'nullable|date|after:today',
            'zdjecie_pogladowe' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'faktura_pdf' => 'nullable|mimes:pdf|max:2048', // Walidacja dla pliku PDF
        ]);
        
        if ($request->hasFile('zdjecie_pogladowe')) {
            $path = $request->file('zdjecie_pogladowe')->store('zdjecia_pogladowe', 'public');
            $validatedData['zdjecie_pogladowe_path'] = Storage::url($path);
        }

        if ($request->hasFile('faktura_pdf')) {
            $path = $request->file('faktura_pdf')->store('faktury','public'); // Zapis do nowego folderu
            $validatedData['faktura_pdf_path'] = Storage::url($path);
        }
        Przedmiot::create($validateData);
        return redirect()->route('items.index')->with('success', 'Przedmiot został pomyślnie dodany.');
    }

    public function edit(Przedmiot $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Przedmiot $item)
    {
        $validateData = $request->validate([
            'nazwa' => 'required|string|max:50',
            'typ' => 'required|string|max:50',
            'rozmiar' => 'required|string|max:50',
            'ilosc_dodanych' => 'required|int',
            'data_waznosci' => 'nullable|date|after:today',
            'zdjecie_pogladowe' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $item->update($validateData);

        return redirect()->route('items.index');
    }

    public function destroy(Przedmiot $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Produkt został pomyślnie usunięty');
    }
}
