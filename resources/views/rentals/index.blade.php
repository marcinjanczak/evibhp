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
                <th width="250px">Akcje</th>
            </tr>
        </thead>
        <tbody>
            <td>Maciek Betka</td>
            <td>Koszulka</td>
            <td>1</td>
            <td>2025-07-11</td>
            <td>2026-07-11</td>
            <td>
                <div class="d-flex gap-2 justify-content-end">
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i> Edytuj
                    </a>
                    <form action="#" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm"
                            onclick="return confirm('Na pewno usunąć?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tbody>
    </table>
    <div>
        <a href="#" class="btn btn-info link-box me-2">
            <i class="fas fa-history"></i> Archiwalne
        </a>
        <a href="#" class="btn btn-success link-box">
            <i class="fas fa-plus"></i> Nowe wypożyczenie
        </a>
    </div>
</main>

@endsection