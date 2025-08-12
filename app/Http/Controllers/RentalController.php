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

        // Użycie relacji, która jest już poprawnie zdefiniowana w modelu Przedmiot
        $item = Przedmiot::with('stanMagazynu')->find($validatedData['IdPrzedmiot']);

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
                // Poprawiona linia: użycie operatora null-coalescing
                'DataPlanowanegoZwrotu' => $validatedData['DataPlanowanegoZwrotu'] ?? null,
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
    public function edit(Wypozyczenie $wypozyczone)
    {
        $employees = Pracownik::all();
        $items = Przedmiot::with('stanMagazynu')->get();

        return view('rentals.edit', compact('wypozyczone', 'employees', 'items'));
    }
    public function update(Request $request, Wypozyczenie $wypozyczenie)
    {
        // 1. Walidacja danych z formularza.
        // Używamy tych samych reguł co w metodzie 'store'.
        $validatedData = $request->validate([
            'IdPracownika' => 'required|exists:pracownicy,id',
            'IdPrzedmiot' => 'required|exists:przedmioty,id',
            'Ilosc' => 'required|integer|min:1',
            'DataWypozyczenia' => 'required|date',
            'DataPlanowanegoZwrotu' => 'nullable|date|after_or_equal:DataWypozyczenia',
        ]);

        // 2. Pobranie starej ilości i nowego zapotrzebowania z walidowanych danych.
        $oldIlosc = (int) $wypozyczenie->Ilosc;
        $validatedIlosc = (int) $validatedData['Ilosc'];

        // 3. Rozpoczęcie transakcji, aby zapewnić spójność danych.
        // Jeśli coś pójdzie nie tak, wszystkie zmiany zostaną wycofane.
        try {
            DB::beginTransaction();

            // 4. Logika aktualizacji stanu magazynu
            // Sprawdzamy, czy przedmiot uległ zmianie
            if ($wypozyczenie->IdPrzedmiot == $validatedData['IdPrzedmiot']) {
                // Przedmiot jest ten sam, więc korygujemy tylko jego ilość.
                $roznica = $validatedIlosc - $oldIlosc;

                // Użycie relacji do aktualizacji stanu magazynu.
                $stan = $wypozyczenie->przedmiot->stanMagazynu;

                if ($stan) {
                    $stan->Ilosc -= $roznica;
                    $stan->save();
                } else {
                    throw new \Exception('Nie znaleziono stanu magazynu dla przedmiotu.');
                }
            } else {
                // Przedmiot został zmieniony, musimy zaktualizować stany obu przedmiotów.
                // a) Zwracamy stary przedmiot do magazynu.
                $oldItemStan = Przedmiot::find($wypozyczenie->IdPrzedmiot)->stanMagazynu;
                if ($oldItemStan) {
                    $oldItemStan->Ilosc += $oldIlosc;
                    $oldItemStan->save();
                }

                // b) Zmniejszamy ilość nowego przedmiotu.
                $newItemStan = Przedmiot::find($validatedData['IdPrzedmiot'])->stanMagazynu;
                if (!$newItemStan || $newItemStan->Ilosc < $validatedIlosc) {
                    throw new \Exception('Niewystarczająca ilość nowego przedmiotu w magazynie.');
                }
                $newItemStan->Ilosc -= $validatedIlosc;
                $newItemStan->save();
            }

            // 5. Zaktualizowanie rekordu wypożyczenia.
            $wypozyczenie->update($validatedData);

            // 6. Zakończenie transakcji.
            DB::commit();

            return redirect()->route('rentals.index')
                ->with('success', 'Wypożyczenie zostało pomyślnie zaktualizowane.');
        } catch (\Exception $e) {
            // W przypadku błędu, wycofujemy transakcję i wracamy z komunikatem błędu.
            DB::rollBack();
            return back()->withInput()->with('error', 'Wystąpił błąd podczas aktualizacji wypożyczenia: ' . $e->getMessage());
        }
    }
}
