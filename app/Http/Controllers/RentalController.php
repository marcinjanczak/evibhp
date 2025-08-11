<?php

namespace App\Http\Controllers;

use App\Models\Pracownik;
use App\Models\Przedmiot;
use App\Models\StanMagazynu;
use App\Models\Wypozyczenie;
use Illuminate\Http\Request;

class RentalController
{
    public function index()
    {
        $rentals = Wypozyczenie::with(['pracownik', 'przedmiot'])->get();

        return view('rentals.index', compact('rentals'));
    }
    public function create()
    {
        $employees = Pracownik::all();
        $items = Przedmiot::with('stanMagazynu')->get();

        return view('rentals.create', compact('employees', 'items'));
    }
    public function destroy(Wypozyczenie $rental)
    {
        StanMagazynu::where('IdPrzedmiot', $rental->IdPrzedmiot)
            ->increment('Ilosc', (int) $rental->Ilosc);

        $rental->delete();

        return redirect()->route('rentals.index')->with('success', 'Wypożyczenie usunięte.');
    }
}
