<?php

namespace App\Http\Controllers;

use App\Models\Przedmiot;
use App\Models\StanMagazynu;
use App\Models\Wypozyczenie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController
{
    public function index()
    {
        $items = Przedmiot::with('stanMagazynu')
            ->orderBy('typ', 'asc')
            ->orderBy('nazwa', 'asc')
            ->get();

        return view('items.index', compact('items'));
    }
    public function create()
    {
        return view('items.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nazwa' => 'required|string|max:50',
            'typ' => 'required|string|max:50',
            'rozmiar' => 'required|string|max:50',
            'ilosc_dodanych' => 'required|int',
            'data_waznosci' => 'nullable|date|after:today',
            'zdjecie_pogladowe' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'faktura_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('zdjecie_pogladowe')) {
            $path = $request->file('zdjecie_pogladowe')->store('zdjecia_pogladowe', 'public');
            $validatedData['zdjecie_pogladowe_path'] = $path;
        }

        if ($request->hasFile('faktura_pdf')) {
            $path = $request->file('faktura_pdf')->store('faktury', 'public');
            $validatedData['faktura_pdf_path'] = $path;
        }

        $przedmiot = Przedmiot::create($validatedData);
        StanMagazynu::create([
            'IdPrzedmiot' => $przedmiot->id,
            'Ilosc' => $validatedData['ilosc_dodanych'],
        ]);

        return redirect()->route('items.index')->with('success', 'Przedmiot został pomyślnie dodany.');
    }

    public function edit(Przedmiot $item)
    {
        return view('items.edit', compact('item'));
    }
    public function update(Request $request, Przedmiot $item)
    {
             $validatedData = $request->validate([
            'nazwa' => 'required|string|max:50',
            'typ' => 'required|string|max:50',
            'rozmiar' => 'required|string|max:50',
            'data_waznosci' => 'nullable|date|after:today',
            'zdjecie_pogladowe' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'faktura_pdf' => 'nullable|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('zdjecie_pogladowe')) {
            if ($item->zdjecie_pogladowe_path) {
                Storage::disk('public')->delete($item->zdjecie_pogladowe_path);
            }
            $path = $request->file('zdjecie_pogladowe')->store('zdjecia_pogladowe', 'public');
            $validatedData['zdjecie_pogladowe_path'] = $path;
        }

        if ($request->hasFile('faktura_pdf')) {
            if ($item->faktura_pdf_path) {
                Storage::disk('public')->delete($item->faktura_pdf_path);
            }
            $path = $request->file('faktura_pdf')->store('faktury', 'public');
            $validatedData['faktura_pdf_path'] = $path;
        }

        $item->update($validatedData);

        return redirect()->route('items.index')->with('success', 'Przedmiot został pomyślnie zaktualizowany.');
    }

    public function destroy(Przedmiot $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Produkt został pomyślnie usunięty');
    }
    public function show(Przedmiot $item)
    {
        $rentals = Wypozyczenie::with('pracownik')
            ->where('IdPrzedmiot', $item->id)
            ->get();
        return view('items.show', compact('item', 'rentals'));
    }
}
