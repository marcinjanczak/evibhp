@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

<div class="container">
    <h3>Edytuj wypożyczenie</h3>
    {{-- Formularz do edycji musi używać metody PUT/PATCH --}}
    <form action="{{ route('rentals.update', $wypozyczone->IdWypozyczenia) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="IdPracownika">Pracownik:</label>
                    <select name="IdPracownika" id="IdPracownika" class="form-control" required>
                        <option value="">Wybierz pracownika</option>
                        {{-- Używamy $employees jak w Twoim formularzu, ale przekazujemy go z kontrolera --}}
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ old('IdPracownika', $wypozyczone->IdPracownika) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->imie }} {{ $employee->nazwisko }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="IdPrzedmiot">Przedmiot:</label>
                    <select name="IdPrzedmiot" id="IdPrzedmiot" class="form-control" required>
                        <option value="">Wybierz przedmiot</option>
                        {{-- Używamy $items jak w Twoim formularzu, ale przekazujemy go z kontrolera --}}
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ old('IdPrzedmiot', $wypozyczone->IdPrzedmiot) == $item->id ? 'selected' : '' }}>
                                {{ $item->nazwa }} (dostępna ilość:
                                {{ $item->stanMagazynu->Ilosc ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="Ilosc">Ilość:</label>
                    <input type="number" name="Ilosc" id="Ilosc" class="form-control"
                        value="{{ old('Ilosc', $wypozyczone->Ilosc) }}" min="1" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="DataWypozyczenia">Data wypożyczenia:</label>
                    <input type="date" name="DataWypozyczenia" id="DataWypozyczenia" class="form-control"
                        value="{{ old('DataWypozyczenia', \Carbon\Carbon::parse($wypozyczone->DataWypozyczenia)->format('Y-m-d')) }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="DataPlanowanegoZwrotu">Data do zwrotu:</label>
                    <input type="date" name="DataPlanowanegoZwrotu" id="DataPlanowanegoZwrotu" class="form-control"
                        value="{{ old('DataPlanowanegoZwrotu', $wypozyczone->DataPlanowanegoZwrotu ? \Carbon\Carbon::parse($wypozyczone->DataPlanowanegoZwrotu)->format('Y-m-d') : '') }}">
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success link-box">
            <i class="fas fa-save"></i> Zaktualizuj
        </button>
    </form>
    <a href="{{ route('rentals.index') }}" class="btn btn-primary link-box">
        <i class="fas fa-arrow-left"></i> Powrót
    </a>
</div>


@endsection
