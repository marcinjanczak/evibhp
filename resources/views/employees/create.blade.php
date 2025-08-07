@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Dodaj nowego pracownika</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="imie" class="form-label">Imię*</label>
                            <input type="text" class="form-control @error('imie') is-invalid @enderror" 
                                   id="imie" name="imie" value="{{ old('imie') }}" required>
                            @error('imie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nazwisko" class="form-label">Nazwisko*</label>
                            <input type="text" class="form-control @error('nazwisko') is-invalid @enderror" 
                                   id="nazwisko" name="nazwisko" value="{{ old('nazwisko') }}" required>
                            @error('nazwisko')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Anuluj
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Dodaj pracownika
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection