@extends('layouts.app')

@section('title', 'Pracownicy')

@section('content')
    <main class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Lista pracowników</h1>
            <a href="{{ route('employees.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Dodaj pracownika
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Nazwisko</th>
                            <th>Imię</th>
                            <th class="text-end">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                {{-- Zmienione na last_name i first_name --}}
                                <td>{{ $employee->last_name }}</td>
                                <td>{{ $employee->first_name }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-info" title="Szczegóły">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary" title="Edytuj">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                onclick="return confirm('Na pewno usunąć pracownika {{ $employee->first_name }} {{ $employee->last_name }}?')" title="Usuń">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Brak pracowników w bazie danych.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection