@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista pracowników</h1>

    <main>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th>Akcje</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->id }}</td>
            <td>{{ $employee->imie }}</td>
            <td>{{ $employee->nazwisko }}</td>
            <td>
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" 
                            onclick="return confirm('Czy na pewno chcesz usunąć tego pracownika?')">
                        <i class="fas fa-trash-alt"></i> Usuń
                    </button>
                </form>
                
                {{-- <!-- Przykład przycisku edycji -->
                <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Edytuj
                </a> --}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>
</main>

@endsection