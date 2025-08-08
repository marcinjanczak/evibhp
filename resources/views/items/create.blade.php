@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Dodaj nowy przedmiot</h3>

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
                            <div class="mb-3">
                                <label>Nazwa:</label>
                                <input type="text" name="nazwa" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Typ:</label>
                                <input type="text" name="typ" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Rozmiar:</label>
                                <input type="text" name="rozmiar" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Ilość:</label>
                                <input type="number" name="ilosc_dodanych" class="form-control" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label>Data używalności</label>
                                <input type="date" name="data_waznosci" class="form-control" required>
                            </div>
                            <a href="{{ route('items.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Powrót
                            </a>

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Zapisz
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
