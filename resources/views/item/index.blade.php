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
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Lista przedmiotów</h3>
                        <a href="{{ route('items.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Dodaj nowy przedmiot
                        </a> 
                    </div>
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

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nazwa</th>
                                    <th>Typ</th>
                                    <th>Rozmiar</th>
                                    <th>Ilość</th>
                                    <th class="text-center">Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($przedmioty as $przedmiot)
                                    <tr>
                                        <td>{{ $przedmiot->Nazwa }}</td>
                                        <td>{{ $przedmiot->Typ }}</td>
                                        <td>{{ $przedmiot->Rozmiar }}</td>
                                        <td>{{ $przedmiot->Ilosc ?? 0 }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary me-2" onclick="openEditModal({{ $przedmiot->IdPrzedmiot }}, '{{ $przedmiot->Nazwa }}', '{{ $przedmiot->Typ }}', '{{ $przedmiot->Rozmiar }}', {{ $przedmiot->Ilosc ?? 0 }})">
                                                <i class="fas fa-edit"></i> Edytuj
                                            </button>
                                            <form action="{{ route('items.destroy', $przedmiot->IdPrzedmiot) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Na pewno usunąć?')">
                                                    <i class="fas fa-trash"></i> Usuń
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal do edycji --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edytuj przedmiot</h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeEditModal()"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="IdPrzedmiot" id="editIdPrzedmiot">
                    <div class="mb-3">
                        <label class="form-label">Nazwa:</label>
                        <input type="text" class="form-control" name="Nazwa" id="editNazwa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Typ:</label>
                        <input type="text" class="form-control" name="Typ" id="editTyp" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rozmiar:</label>
                        <input type="text" class="form-control" name="Rozmiar" id="editRozmiar">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ilość:</label>
                        <input type="number" class="form-control" name="Ilosc" id="editIlosc" min="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                            <i class="fas fa-times"></i> Anuluj
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Zapisz zmiany
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection