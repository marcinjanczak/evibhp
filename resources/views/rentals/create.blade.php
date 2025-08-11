@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header site-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-white">Nowe wypożyczenie</h3>
                        <a href="{{ route('rentals.index') }}" class="btn btn-primary link-box">
                            <i class="fas fa-arrow-left"></i> Powrót
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="links-vievs p-4">
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

                        <form action="{{ route('rentals.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="IdPracownika">Pracownik:</label>
                                        <select name="IdPracownika" id="IdPracownika" class="form-control" required>
                                            <option value="">Wybierz pracownika</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('IdPracownika') == $employee->id ? 'selected' : '' }}>
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
                                            @foreach($items as $item)
                                                <option value="{{ $item->id }}" {{ old('IdPrzedmiot') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nazwa }} (dostępna ilość: {{ $item->stanMagazynu->Ilosc ?? 0 }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="Ilosc">Ilość:</label>
                                        <input type="number" name="Ilosc" id="Ilosc" class="form-control" value="{{ old('Ilosc', 1) }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="DataWypozyczenia">Data wypożyczenia:</label>
                                        <input type="datetime-local" name="DataWypozyczenia" id="DataWypozyczenia" class="form-control" 
                                            value="{{ old('DataWypozyczenia', date('Y-m-d\TH:i')) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="DataPlanowanegoZwrotu">Data do zwrotu:</label>
                                        <input type="datetime-local" name="DataPlanowanegoZwrotu" id="DataPlanowanegoZwrotu" class="form-control" 
                                            value="{{ old('DataPlanowanegoZwrotu', date('Y-m-d\TH:i', strtotime('+7 days'))) }}">
                                    </div>
                                </div>
                                <div class="col-12 text-center mt-3">
                                    <button type="submit" class="btn btn-success link-box">
                                        <i class="fas fa-save"></i> Zapisz
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
