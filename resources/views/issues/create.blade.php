@extends('layouts.app')

@section('title', 'Nowe Wydanie')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3>Dodaj nowe wydanie</h3>
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

                        {{-- Zmieniony route --}}
                        <form action="{{ route('issues.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="employee_id">Pracownik</label>
                                        <select name="employee_id" id="employee_id" class="form-control" required>
                                            <option value="">-- Wybierz pracownika --</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->last_name }} {{ $employee->first_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="product_id">Przedmiot</label>
                                        <select name="product_id" id="product_id" class="form-control" required>
                                            <option value="">-- Wybierz przedmiot --</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }} (dostępna ilość:
                                                    {{ $product->inventory->quantity ?? 0 }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="quantity">Ilość</label>
                                        <input type="number" name="quantity" id="quantity" class="form-control"
                                            value="{{ old('quantity', 1) }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="issued_at">Dzień wydania</label>
                                        <input type="date" name="issued_at" id="issued_at"
                                            class="form-control"
                                            value="{{ old('issued_at', now()->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="due_date">Data końca wydania (opcjonalne)</label>
                                        <input type="date" name="due_date" id="due_date"
                                            class="form-control"
                                            value="{{ old('due_date') }}">
                                        <small class="text-muted">Jeśli zostawisz puste, system użyje daty ważności produktu.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('issues.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Powrót
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Zapisz wydanie
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection