@extends('layouts.app')

@section('title', 'Dodaj Pracownika')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Dodaj nowego pracownika</h3>
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

                        <form method="POST" action="{{ route('employees.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Imię*</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="last_name" class="form-label">Nazwisko*</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
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