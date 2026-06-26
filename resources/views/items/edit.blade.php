@extends('layouts.app')

@section('title', 'Edytuj Przedmiot')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Edytuj przedmiot: {{ $item->name }}</h3>
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

                        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nazwa*</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Typ*</label>
                                    <input type="text" name="type" class="form-control" value="{{ old('type', $item->type) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Rozmiar*</label>
                                    <input type="text" name="size" class="form-control" value="{{ old('size', $item->size) }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Data używalności/ważności</label>
                                    <input type="date" name="expiration_date" class="form-control" 
                                           value="{{ old('expiration_date', $item->expiration_date ? $item->expiration_date->format('Y-m-d') : '') }}">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="preview_image" class="form-label">Zdjęcie poglądowe</label>
                                    @if ($item->preview_image_path)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $item->preview_image_path) }}" alt="Zdjęcie" class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="preview_image" name="preview_image" accept="image/*">
                                    <small class="text-muted">Wybierz plik, aby zastąpić obecne zdjęcie.</small>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="invoice_pdf" class="form-label">Faktura (PDF)</label>
                                    @if ($item->invoice_pdf_path)
                                        <div class="mb-2">
                                            <a href="{{ asset('storage/' . $item->invoice_pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-file-pdf"></i> Pokaż obecną fakturę
                                            </a>
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="invoice_pdf" name="invoice_pdf" accept="application/pdf">
                                    <small class="text-muted">Wybierz plik, aby zastąpić obecny dokument PDF.</small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end border-top pt-3">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save"></i> Zapisz zmiany
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection