@extends('layouts.app')

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
                    {{-- @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Błąd!</strong> Sprawdź wprowadzone dane.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}
                    <form action="{{ route('items.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label>Nazwa:</label>
                            <input type="text" name="nazwa" class="form-control @error('nazwa') is-invalid @enderror" 
                                   id="nazwa" name="nazwa" value="{{ old('nazwa', $item->nazwa) }}" required>
                            @error('nazwa')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                       
                        <div class="mb-3">
                            <label>Typ:</label>
                            <input type="text" name="typ" class="form-control @error('typ') is-invalid @enderror" 
                                   id="typ" name="typ" value="{{ old('typ', $item->typ) }}" required>
                            @error('typ')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Rozmiar:</label>
                            <input type="text" name="rozmiar" class="form-control @error('rozmiar') is-invalid @enderror" 
                                   id="rozmiar" name="rozmiar" value="{{ old('rozmiar', $item->rozmiar) }}" required>
                            @error('rozmiar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror 
                        </div>
                        
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Zapisz
                        </button>
                    </form>



                    {{-- <form action="{{ route('items.store') }}" method="POST">
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
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Zapisz
                                </button>
                            </div>
                        </div>
                    </form> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



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