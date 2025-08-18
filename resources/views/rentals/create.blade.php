@extends('layouts.app')

@section('title', 'Nowe Wydanie')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Dodaj nowe wydanie</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Błąd!</strong> Sprawdź wprowadzone dane.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('rentals.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="IdPracownika">Pracownik</label>
                                        <select name="IdPracownika" id="IdPracownika" class="form-control" required>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ old('IdPracownika') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->nazwisko }} {{ $employee->imie }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="IdPrzedmiot">Przedmiot</label>
                                        <select name="IdPrzedmiot" id="IdPrzedmiot" class="form-control" required>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('IdPrzedmiot') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nazwa }} (dostępna ilość:
                                                    {{ $item->stanMagazynu->Ilosc ?? 0 }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="Ilosc">Ilość</label>
                                        <input type="number" name="Ilosc" id="Ilosc" class="form-control"
                                            value="{{ old('Ilosc', 1) }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="DataWypozyczenia">Dzień wydania</label>
                                        <input type="date" name="DataWypozyczenia" id="DataWypozyczenia"
                                            class="form-control"
                                            value="{{ old('DataWypozyczenia', now()->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="DataPlanowanegoZwrotu">Data końca wydania (opcjonalne)</label>
                                        <input type="date" name="DataPlanowanegoZwrotu" id="DataPlanowanegoZwrotu"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('rentals.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Powrót
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Zapisz
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
