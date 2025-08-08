<?php

namespace App\Http\Controllers;

use App\Models\Wypozyczone;
use App\Models\Pracownik;
use App\Models\Przedmiot;
use App\Models\StanPrzedmiotu;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $wypozyczenia = Wypozyczone::with(['pracownik', 'przedmiot'])
            ->whereNull('DataZwrotu')
            ->orderBy('Data', 'desc')
            ->get();

        return view('wypozyczone.index', compact('wypozyczenia'));
    }

    public function create()
    {
        $pracownicy = Pracownik::all();
        $przedmiot = Przedmiot::whereHas('stan', function ($query) {
            $query->where('Ilosc', '>', 0);
        })->get();

        return view('wypozyczone.create', compact('pracownicy', 'przedmiot'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'IdPracownika' => 'required|exists:pracownicy,id',
            'IdPrzedmiot' => 'required|exists:przedmiot,IdPrzedmiot',
            'Ilosc' => 'required|integer|min:1',
            'Data' => 'required|date',
            'DataDoZwrotu' => 'required|date|after_or_equal:Data',
        ]);

        $stan = StanPrzedmiotu::where('IdPrzedmiot', $validated['IdPrzedmiot'])->first();

        if (!$stan || $stan->Ilosc < $validated['Ilosc']) {
            return back()->with('error', 'Brak wystarczającej ilości przedmiotów.');
        }

        Wypozyczone::create($validated);
        $stan->decrement('Ilosc', (int) $validated['Ilosc']);

        return redirect()->route('wypozyczone.index')->with('success', 'Dodano wypożyczenie');
    }

    public function edit($id)
    {
        $wypozyczone = Wypozyczone::findOrFail($id);
        $pracownicy = Pracownik::all();
        $przedmiot = Przedmiot::all();

        return view('wypozyczone.edit', compact('wypozyczone', 'pracownicy', 'przedmiot'));
    }

    public function update(Request $request, Wypozyczone $wypozyczone)
    {
        $validated = $request->validate([
            'IdPracownika' => 'required|exists:pracownicy,id',
            'IdPrzedmiot' => 'required|exists:przedmiot,IdPrzedmiot',
            'Ilosc' => 'required|integer|min:1',
            'Data' => 'required|date',
            'DataDoZwrotu' => 'required|date|after_or_equal:Data',
        ]);

        $validatedIlosc = (int) $validated['Ilosc'];
        $oldIlosc = (int) $wypozyczone->Ilosc;

        if ($wypozyczone->IdPrzedmiot == $validated['IdPrzedmiot']) {
            $roznica = $validatedIlosc - $oldIlosc;

            if ($roznica > 0) {
                StanPrzedmiotu::where('IdPrzedmiot', $validated['IdPrzedmiot'])->decrement('Ilosc', $roznica);
            } elseif ($roznica < 0) {
                StanPrzedmiotu::where('IdPrzedmiot', $validated['IdPrzedmiot'])->increment('Ilosc', abs($roznica));
            }
        } else {
            StanPrzedmiotu::where('IdPrzedmiot', $wypozyczone->IdPrzedmiot)->increment('Ilosc', $oldIlosc);
            StanPrzedmiotu::where('IdPrzedmiot', $validated['IdPrzedmiot'])->decrement('Ilosc', $validatedIlosc);
        }

        $wypozyczone->update($validated);

        return redirect()->route('wypozyczone.index')->with('success', 'Zaktualizowano wypożyczenie');
    }

    public function destroy(Wypozyczone $wypozyczone)
    {
        StanPrzedmiotu::where('IdPrzedmiot', $wypozyczone->IdPrzedmiot)
            ->increment('Ilosc', (int) $wypozyczone->Ilosc);

        $wypozyczone->delete();

        return redirect()->route('wypozyczone.index')->with('success', 'Wypożyczenie usunięte');
    }

    public function date()
    {
        $archiwalne = Wypozyczone::with(['pracownik', 'przedmiot'])
            ->whereNotNull('DataZwrotu')
            ->orderBy('DataZwrotu', 'desc')
            ->get();

        return view('wypozyczone.date', compact('archiwalne'));
    }

public function markAsReturned(Wypozyczone $wypozyczone)
{
    if ($wypozyczone->DataZwrotu !== null) {
        return redirect()->route('wypozyczone.index')->with('info', 'To wypożyczenie zostało już zakończone.');
    }

    $wypozyczone->update([
        'DataZwrotu' => now(),
    ]);

    StanPrzedmiotu::where('IdPrzedmiot', $wypozyczone->IdPrzedmiot)
        ->increment('Ilosc', (int) $wypozyczone->Ilosc);

    return redirect()->route('wypozyczone.index')->with('success', 'Przedmiot został zwrócony i stan zaktualizowany.');
}

}
