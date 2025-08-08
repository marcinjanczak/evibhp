
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header site-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-white">Edytuj wypożyczenie</h3>
                        <a href="{{ route('wypozyczone.index') }}" class="btn btn-primary link-box">
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

                        <form action="{{ route('wypozyczone.update', $wypozyczone->IdWypozyczenia) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Pracownik:</label>
                                        <select name="IdPracownika" class="form-control" required>
                                            @foreach($pracownicy as $pracownik)
                                                <option value="{{ $pracownik->id }}" 
                                                        {{ $wypozyczone->IdPracownika == $pracownik->id ? 'selected' : '' }}>
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
                                            @foreach($przedmiot as $przedmiot)
                                                <option value="{{ $przedmiot->IdPrzedmiot }}"
                                                        {{ $wypozyczone->IdPrzedmiot == $przedmiot->IdPrzedmiot ? 'selected' : '' }}>
                                                    {{ $przedmiot->Nazwa }} ({{ $przedmiot->Typ }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label>Ilość:</label>
                                        <input type="number" name="Ilosc" class="form-control" 
                                               value="{{ $wypozyczone->Ilosc }}" min="1" required>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Data wypożyczenia:</label>
                                    <input type="datetime-local" name="Data" class="form-control" 
                                        value="{{ date('Y-m-d\TH:i', strtotime($wypozyczone->Data)) }}" required>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Data do zwrotu:</label>
                                    <input type="datetime-local" name="DataDoZwrotu" class="form-control" 
                                        value="{{ $wypozyczone->DataDoZwrotu ? date('Y-m-d\TH:i', strtotime($wypozyczone->DataDoZwrotu)) : '' }}">
                                </div>

                            </div>
                            <div class="col-12 text-center mt-3">

                                <div class="col-12 text-center mt-3">
                                    <button type="submit" class="btn btn-success link-box">
                                        <i class="fas fa-save"></i> Zapisz zmiany
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