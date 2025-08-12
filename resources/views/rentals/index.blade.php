@extends('layouts.app')

@section('content')
    <main>
        <h3>Lista wydań</h3>
        <table class="table table-hover">
            <thead class="bg-light">
                <tr>
                    <th>Pracownik</th>
                    <th>Przedmiot</th>
                    <th>Ilość</th>
                    <th>Data wypożyczenia</th>
                    <th>Data do zwrotu</th>
                    <th class="text-end">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rentals as $rental)
                <tr>
                    <td>{{ $rental->pracownik->imie}} {{$rental->pracownik->nazwisko}}</td>
                    <td>{{ $rental->przedmiot->nazwa}}</td>
                    <td>{{ $rental->Ilosc}}</td>
                    <td>{{ $rental->DataWypozyczenia}}</td>
                    <td>{{ $rental->DataPlanowanegoZwrotu}}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edytuj
                            </a>
                            <form action="{{ route('rentals.destroy', $rental->id) }}" method="POST">
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
        <div>
            {{-- <a href="#" class="btn btn-info link-box me-2">
                <i class="fas fa-history"></i> Archiwalne
            </a> --}}
            <a href="{{ route('rentals.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nowe wypożyczenie
            </a>
        </div>
    </main>
@endsection
