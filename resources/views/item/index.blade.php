@extends('layouts.app')

@section('title', 'Zarządzanie przedmiotami')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Lista przedmiotów</h2>
                </div>
                
                <div class="card-body">
                    {{-- Formularz dodawania nowego przedmiotu --}}
                    <div class="mb-4 p-3 bg-light rounded">
                        <h4 class="card-title">Dodaj nowy przedmiot</h4>
                        <form action="{{ route('items.store') }}" method="POST" class="row g-3">
                            @csrf
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="Nazwa" placeholder="Nazwa" required>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="Typ" placeholder="Typ" required>
                            </div>
                            <div class="col-md-2">
                                <input type="text" class="form-control" name="Rozmiar" placeholder="Rozmiar">
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" name="Ilosc" placeholder="Ilość" min="0" value="0">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus"></i> Dodaj
                                </button>
                            </div>
                        </form>
                    </div>

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
</div>
@endsection