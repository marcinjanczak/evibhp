@extends('layouts.app')

@section('title', 'Edytuj Przedmiot')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Edytuj przedmiot</h3>
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
                        {{-- Formularz do edycji z metodą PUT/PATCH --}}
                        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') {{-- Niezbędny tag dla aktualizacji w Laravelu --}}

                            {{-- Pola formularza wypełnione danymi z bazy --}}
                            <div class="mb-3">
                                <label>Nazwa*</label>
                                <input type="text" name="nazwa" class="form-control" value="{{ old('nazwa', $item->nazwa) }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Typ*</label>
                                <input type="text" name="typ" class="form-control" value="{{ old('typ', $item->typ) }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Rozmiar*</label>
                                <input type="text" name="rozmiar" class="form-control" value="{{ old('rozmiar', $item->rozmiar) }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Data używalności</label>
                                <input type="date" name="data_waznosci" class="form-control" value="{{ old('data_waznosci', $item->data_waznosci ? \Carbon\Carbon::parse($item->data_waznosci)->format('Y-m-d') : '') }}">
                            </div>

                            {{-- Sekcja do edycji zdjęcia --}}
                            <div class="mb-3">
                                <label for="zdjecie_pogladowe" class="form-label">Zdjęcie poglądowe</label>
                                @if ($item->zdjecie_pogladowe_path)
                                    <p>Obecne zdjęcie:</p>
                                    <img src="{{ asset('storage/' . $item->zdjecie_pogladowe_path) }}" alt="Zdjęcie poglądowe" style="max-width: 200px; display: block; margin-bottom: 10px;">
                                    <small class="form-text text-muted">Przesłanie nowego zdjęcia zastąpi obecne.</small>
                                @endif
                                <input type="file" class="form-control" id="zdjecie_pogladowe" name="zdjecie_pogladowe">
                            </div>

                            {{-- Sekcja do edycji faktury --}}
                            <div class="mb-3">
                                <label for="faktura_pdf" class="form-label">Faktura (PDF)</label>
                                @if ($item->faktura_pdf_path)
                                    <p>Obecna faktura: <a href="{{ asset('storage/' . $item->faktura_pdf_path) }}" target="_blank">Pokaż fakturę</a></p>
                                    <small class="form-text text-muted">Przesłanie nowego pliku PDF zastąpi obecny.</small>
                                @endif
                                <input type="file" class="form-control" id="faktura_pdf" name="faktura_pdf">
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Zapisz Zmiany
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
