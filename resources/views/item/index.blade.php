{{-- filepath: resources/views/item/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Zarządzanie przedmiotami')

@section('content')
    <h1>Lista przedmiotów</h1>
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
<script src="{{ asset('js/items.js') }}"></script>

    {{-- Formularz dodawania nowego przedmiotu --}}
    <h3>Dodaj nowy przedmiot</h3>
    <form action="{{ route('items.store') }}" method="POST">
        @csrf
        <input type="text" name="Nazwa" placeholder="Nazwa" required>
        <input type="text" name="Typ" placeholder="Typ" required>
        <input type="text" name="Rozmiar" placeholder="Rozmiar">
        <input type="number" name="Ilosc" placeholder="Ilość" min="0" value="0">
        <button type="submit">Dodaj</button>
    </form>
    <br>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Nazwa</th>
                <th>Typ</th>
                <th>Rozmiar</th>
                <th>Ilość</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($przedmioty as $przedmiot)
                <tr>
                    <td>{{ $przedmiot->Nazwa }}</td>
                    <td>{{ $przedmiot->Typ }}</td>
                    <td>{{ $przedmiot->Rozmiar }}</td>
                    <td>{{ $przedmiot->Ilosc ?? 0 }}</td>
                    <td>
                        <button onclick="openEditModal({{ $przedmiot->IdPrzedmiot }}, '{{ $przedmiot->Nazwa }}', '{{ $przedmiot->Typ }}', '{{ $przedmiot->Rozmiar }}', {{ $przedmiot->Ilosc ?? 0 }})">Edytuj</button>
                        <form action="{{ route('items.destroy', $przedmiot->IdPrzedmiot) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Na pewno usunąć?')">Usuń</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal do edycji --}}
    <div id="editModal" >
        <div >
            <button onclick="closeEditModal()">X</button>
            <h3>Edytuj przedmiot</h3>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="IdPrzedmiot" id="editIdPrzedmiot">
                <label>Nazwa: <input type="text" name="Nazwa" id="editNazwa" required></label><br>
                <label>Typ: <input type="text" name="Typ" id="editTyp" required></label><br>
                <label>Rozmiar: <input type="text" name="Rozmiar" id="editRozmiar"></label><br>
                <label>Ilość: <input type="number" name="Ilosc" id="editIlosc" min="0"></label><br>
                <button type="submit">Zapisz</button>
            </form>
        </div>
    </div>

@endsection