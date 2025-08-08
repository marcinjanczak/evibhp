{{-- @extends('layouts.app')

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

                    <form action="{{ route('items.update', $przedmiot->IdPrzedmiot) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Nazwa:</label>
                                    <input type="text" name="Nazwa" class="form-control" value="{{ $przedmiot->Nazwa }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Typ:</label>
                                    <input type="text" name="Typ" class="form-control" value="{{ $przedmiot->Typ }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Rozmiar:</label>
                                    <input type="text" name="Rozmiar" class="form-control" value="{{ $przedmiot->Rozmiar }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Ilość:</label>
                                    <input type="number" name="Ilosc" class="form-control" value="{{ $przedmiot->Ilosc ?? 0 }}" min="0">
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-success">
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
@endsection --}}