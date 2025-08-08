<?php

namespace App\Http\Controllers;

use App\Models\Pracownik;
use App\Models\Przedmiot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController {
    public function index() {
        $items = Przedmiot::all();
        return view('item.index', compact('items'));
    }
    public function create() {
        return view('item.create');
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

    public function edit(Przedmiot $id) {
        return view('item.edit', compact('przedmiot'));
    }

    public function update(Request $request, Przedmiot $przedmiot) {
         $validateData = $request->validate([
            'nazwa' => 'required|string|max:50',
            'typ' => 'required|string|max:50',
            'rozmiar' => 'required|string|max:50',
        ]);
        $przedmiot->update($validateData);

        return redirect()->route('items.index');
    }

    public function destroy($id) {
        $przedmiot = Przedmiot::findOrFail($id);
        $przedmiot->delete();

        return redirect()->route('items.index')->with('success', 'Produkt został pomyślnie usunięty');
    }
}