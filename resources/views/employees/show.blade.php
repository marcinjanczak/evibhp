@extends('layouts.app')

@section('title', 'Szczegóły pracownika')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0 text-primary">Karta pracownika</h3>
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Powrót do listy
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-4">
                                <p class="text-muted mb-1">Imię i Nazwisko</p>
                                <h5>{{ $employee->first_name }} {{ $employee->last_name }}</h5>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-muted mb-1">ID Systemowe</p>
                                <h5>#{{ $employee->id }}</h5>
                            </div>
                        </div>

                        <hr>

                        <h4 class="mb-3">Historia wydań i wypożyczeń</h4>
                        
                        @if ($rentals->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Przedmiot</th>
                                            <th>Ilość</th>
                                            <th>Data wydania</th>
                                            <th>Data wymiany/zwrotu</th>
                                            <th class="text-end">Akcje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rentals as $issue)
                                            <tr>
                                                <td class="fw-bold text-dark">{{ $issue->product->name }}</td>
                                                <td><span class="badge bg-info text-dark">{{ $issue->quantity }} szt.</span></td>
                                                <td>{{ $issue->issued_at->format('Y-m-d') }}</td>
                                                <td>
                                                    @if($issue->due_date)
                                                        <span class="{{ $issue->due_date->isPast() ? 'text-danger fw-bold' : '' }}">
                                                            {{ $issue->due_date->format('Y-m-d') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">Brak daty</span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <form action="{{ route('issues.destroy', $issue->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Czy na pewno chcesz usunąć to wydanie?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-light border text-center py-4">
                                <i class="fas fa-info-circle mb-2 fa-2x"></i>
                                <p class="mb-0">Ten pracownik nie ma jeszcze przypisanych żadnych przedmiotów.</p>
                                <a href="{{ route('issues.create', ['employee_id' => $employee->id]) }}" class="btn btn-sm btn-success mt-2">
                                    Wydaj przedmiot teraz
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection