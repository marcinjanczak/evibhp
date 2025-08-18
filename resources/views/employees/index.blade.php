@extends('layouts.app')

@section('title', 'Pracownicy')

@section('content')
    <main>
        <h1>Lista pracowników</h1>
        <table class="table table-hover">
            <thead class="bg-light">
                <tr>
                    {{-- <th class="text-nowrap">ID</th> --}}
                    <th>Nazwisko</th>
                    <th>Imię</th>
                    <th class="text-end">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->nazwisko }}</td>
                        <td>{{ $employee->imie }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> Szczegóły
                                </a>
                                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edytuj
                                </a>
                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                        onclick="return confirm('Na pewno usunąć?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('employees.create') }}" class="btn btn-success align-self-center">
            <i class="fas fa-plus"></i> Dodaj pracownika
        </a>
    </main>
@endsection
