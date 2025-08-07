@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Dodaj nowy przedmiot</h3>
                        <a href="{{ route('items.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Powrót
                        </a>
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

                    <form action="{{ route('items.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nazwa:</label>
                                    <input type="text" name="Nazwa" class="form-control" placeholder="Wprowadź nazwę" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Typ:</label>
                                    <input type="text" name="Typ" class="form-control" placeholder="Wprowadź typ" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Rozmiar:</label>
                                    <input type="text" name="Rozmiar" class="form-control" placeholder="Wprowadź rozmiar">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Ilość:</label>
                                    <input type="number" name="Ilosc" class="form-control" placeholder="Wprowadź ilość" min="0" value="0">
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success">
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
@endsection