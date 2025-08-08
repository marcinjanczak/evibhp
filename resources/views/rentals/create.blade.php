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

                        <form action="{{ route('rentals.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Pracownik:</label>
                                        <select name="IdPracownika" class="form-control" required>
                                            @foreach($pracownicy as $pracownik)
                                                <option value="{{ $pracownik->id }}">
                                                    {{ $pracownik->imie }} {{ $pracownik->nazwisko }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Przedmiot:</label>
                                    <select name="IdPrzedmiot" class="form-control" required>
                                        @foreach($przedmiot as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->Nazwa }} ({{ $item->Typ }})
                                            </option>
                                        @endforeach
                                    </select>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Ilość:</label>
                                        <input type="number" name="Ilosc" class="form-control" min="1" required>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Data wypożyczenia:</label>
                                    <input type="datetime-local" name="Data" class="form-control" 
                                        value="{{ date('Y-m-d\TH:i', strtotime(now())) }}" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Data do zwrotu:</label>
                                    <input type="datetime-local" name="DataDoZwrotu" class="form-control" 
                                        value="{{ old('DataDoZwrotu', date('Y-m-d\TH:i', strtotime('+7 days'))) }}" required>
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