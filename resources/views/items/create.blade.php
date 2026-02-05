@extends('layouts.app')

@section('title', 'Dodaj Przedmiot')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Dodaj nowy przedmiot</h3>
                            <a href="{{ route('items.index') }}" class="btn btn-outline-primary">
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
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nazwa przedmiotu*</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="np. Kask ochronny" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Typ/Kategoria*</label>
                                    <input type="text" name="type" class="form-control" value="{{ old('type') }}" placeholder="np. ŚOI" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Rozmiar*</label>
                                    <input type="text" name="size" class="form-control" value="{{ old('size') }}" placeholder="np. L, XL, 42" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ilość początkowa*</label>
                                    <input type="number" name="quantity_added" class="form-control" value="{{ old('quantity_added', 0) }}" min="0" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Data używalności/ważności*</label>
                                    <input type="date" name="expiration_date" class="form-control" value="{{ old('expiration_date') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="preview_image" class="form-label">Zdjęcie poglądowe</label>
                                    <input type="file" class="form-control" id="preview_image" name="preview_image" accept="image/*">
                                    <small class="text-muted">Formaty: JPG, PNG. Max: 5MB</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="invoice_pdf" class="form-label">Faktura (PDF)</label>
                                    <input type="file" class="form-control" id="invoice_pdf" name="invoice_pdf" accept="application/pdf">
                                    <small class="text-muted">Tylko pliki PDF. Max: 5MB</small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success btn-lg px-5">
                                    <i class="fas fa-save"></i> Zapisz przedmiot
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection