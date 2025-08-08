<?php

namespace App\Http\Controllers;

use App\Models\Pracownik;
use App\Models\Przedmiot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController {
    public function index() {
        $items = Przedmiot::all();
        return view('items.index', compact('items'));
    }
    public function create() {
        return view('items.create');
    }
    public function store(Request $request){
        $validateData = $request->validate([
            'nazwa' => 'required|string|max:50',
            'typ' => 'required|string|max:50',
            'rozmiar' => 'required|string|max:50',
        ]);
        Przedmiot::create($validateData);
        return redirect()->route('items.index');
    }

    public function edit(Przedmiot $item) {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Przedmiot $item) {
         $validateData = $request->validate([
            'nazwa' => 'required|string|max:50',
            'typ' => 'required|string|max:50',
            'rozmiar' => 'required|string|max:50',
        ]);
        $item->update($validateData);

        return redirect()->route('items.index');
    }

    public function destroy(Przedmiot $item) {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Produkt został pomyślnie usunięty');
    }
}