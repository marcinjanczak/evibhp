<?php

namespace App\Http\Controllers;

use App\Models\Przedmiot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController
{
    public function index()
    {
        $przedmiot = Przedmiot::all();
        // $przedmioty = DB::table('przedmiot')
        //     ->leftJoin('stanprzedmiotow', 'przedmiot.IdPrzedmiot', '=', 'stanprzedmiotow.IdPrzedmiot')
        //     ->select(
        //         'przedmiot.IdPrzedmiot',
        //         'przedmiot.Nazwa',
        //         'przedmiot.Typ',
        //         'przedmiot.Rozmiar',
        //         'stanprzedmiotow.Ilosc'
        //     )
        //     ->get();

        return view('item.index', compact('przedmioty'));
    }

    public function create()
    {
        return view('item.create');
    }

    public function store(Request $request)
    {
        $id = DB::table('przedmiot')->insertGetId([
            'Nazwa' => $request->Nazwa,
            'Typ' => $request->Typ,
            'Rozmiar' => $request->Rozmiar,
        ]);
        DB::table('stanprzedmiotow')->insert([
            'IdPrzedmiot' => $id,
            'Ilosc' => $request->Ilosc ?? 0,
        ]);
        return redirect()->route('items.index');
    }

    public function edit($id)
    {
        $przedmiot = DB::table('przedmiot')
            ->leftJoin('stanprzedmiotow', 'przedmiot.IdPrzedmiot', '=', 'stanprzedmiotow.IdPrzedmiot')
            ->select(
                'przedmiot.IdPrzedmiot',
                'przedmiot.Nazwa',
                'przedmiot.Typ',
                'przedmiot.Rozmiar',
                'stanprzedmiotow.Ilosc'
            )
            ->where('przedmiot.IdPrzedmiot', $id)
            ->first();

        return view('item.edit', compact('przedmiot'));
    }

    public function update(Request $request, $id)
    {
        DB::table('przedmiot')->where('IdPrzedmiot', $id)->update([
            'Nazwa' => $request->Nazwa,
            'Typ' => $request->Typ,
            'Rozmiar' => $request->Rozmiar,
        ]);
        DB::table('stanprzedmiotow')->where('IdPrzedmiot', $id)->update([
            'Ilosc' => $request->Ilosc ?? 0,
        ]);
        return redirect()->route('items.index');
    }

    public function destroy($id)
    {
        DB::table('stanprzedmiotow')->where('IdPrzedmiot', $id)->delete();
        DB::table('przedmiot')->where('IdPrzedmiot', $id)->delete();
        return redirect()->route('items.index');
    }
}