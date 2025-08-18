@extends('layouts.app')

@section('title', 'Szczegóły pracownika')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header site-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Szczegóły pracownika</h3>
                            <a href="{{ route('employees.index') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Powrót do listy
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>ID:</strong> {{ $employee->id }}</li>
                            <li class="list-group-item"><strong>Imię:</strong> {{ $employee->imie }}</li>
                            <li class="list-group-item"><strong>Nazwisko:</strong> {{ $employee->nazwisko }}</li>
                        </ul>
                    </div>
                    @if (!$rentals->isEmpty())
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Przedmiot</th>
                                    <th>Ilość</th>
                                    <th>Data wypożyczenia</th>
                                    <th>Data do zwrotu</th>
                                    <th>Akcje</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rentals as $rental)
                                    <tr>
                                        <td>{{ $rental->przedmiot->nazwa }}</td>
                                        <td>{{ $rental->Ilosc }}</td>
                                        <td>{{ $rental->DataWypozyczenia }}</td>
                                        <td>{{ $rental->DataPlanowanegoZwrotu }}</td>
                                        <td>
                                            <form action="{{ route('rentals.destroy', $rental->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Na pewno usunąć?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
