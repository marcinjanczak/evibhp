@extends('layouts.app')

@section('title', 'Dodaj Przedmiot')

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
                        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label>Nazwa*</label>
                                <input type="text" name="nazwa" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Typ*</label>
                                <input type="text" name="typ" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Rozmiar*</label>
                                <input type="text" name="rozmiar" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Ilość*</label>
                                <input type="number" name="ilosc_dodanych" class="form-control" min="0" required>
                            </div>
                            <div class="mb-3">
                                <label>Data używalności*</label>
                                <input type="date" name="data_waznosci" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="zdjecie_pogladowe" class="form-label">Zdjęcie poglądowe</label>
                                <input type="file" class="form-control" id="zdjecie_pogladowe" name="zdjecie_pogladowe">
                            </div>
                            <div class="mb-3">
                                <label for="faktura_pdf" class="form-label">Faktura (PDF)</label>
                                <input type="file" class="form-control" id="faktura_pdf" name="faktura_pdf">
                            </div>

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
