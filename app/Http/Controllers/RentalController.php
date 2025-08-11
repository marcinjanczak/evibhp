<?php

namespace App\Http\Controllers;

use App\Models\Pracownik;
use App\Models\Przedmiot;
use App\Models\StanMagazynu;
use App\Models\Wypozyczenie;
use Illuminate\Support\Facades\DB;
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'IdPracownika' => 'required|exists:pracownicy,id',
            'IdPrzedmiot' => 'required|exists:przedmioty,id',
            'Ilosc' => 'required|integer|min:1',
            'DataWypozyczenia' => 'required|date',
            'DataPlanowanegoZwrotu' => 'nullable|date|after_or_equal:DataWypozyczenia',
        ]);

        $item = Przedmiot::find($validatedData['IdPrzedmiot']);

        if (!$item->stanMagazynu || $item->stanMagazynu->Ilosc < $validatedData['Ilosc']) {
            return back()->withInput()->with('error', 'Niewystarczająca ilość przedmiotu w magazynie.');
        }

        try {
            DB::beginTransaction();
            $rental = Wypozyczenie::create([
                'IdPracownika' => $validatedData['IdPracownika'],
                'IdPrzedmiot' => $validatedData['IdPrzedmiot'],
                'Ilosc' => $validatedData['Ilosc'],
                'DataWypozyczenia' => $validatedData['DataWypozyczenia'],
                'DataPlanowanegoZwrotu' => $validatedData['DataPlanowanegoZwrotu'],
            ]);

            $item->stanMagazynu->Ilosc -= $validatedData['Ilosc'];
            $item->stanMagazynu->save();

            DB::commit();

            return redirect()->route('rentals.index')
                ->with('success', 'Wypożyczenie zostało pomyślnie utworzone.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Wystąpił błąd podczas tworzenia wypożyczenia: ' . $e->getMessage());
        }
    }
    public function destroy(Wypozyczenie $rental)
    {
        StanMagazynu::where('IdPrzedmiot', $rental->IdPrzedmiot)
            ->increment('Ilosc', (int) $rental->Ilosc);

        $rental->delete();

        return redirect()->route('rentals.index')->with('success', 'Wypożyczenie usunięte.');
    }
}
