@extends('layouts.app')

@section('content')
    <main>
        <h3>Lista przedmiotów</h3>
        <table class="table table-hover">
            <thead class="bg-light">
                <tr>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Typ</th>
                    <th>Rozmiar</th>
                    <th>Ilość</th>
                    <th>Data Używalności</th>
                    <th class="text-end">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->nazwa }}</td>
                        <td>{{ $item->typ }}</td>
                        <td>{{ $item->rozmiar }}</td>
                        <td>{{ $item->ilosc_dodanych }}</td>
                        <td>{{ $item->data_waznosci }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edytuj
                                </a>
                                <form action="{{ route('items.destroy', $item->id) }}" method="POST">
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
        <a href="{{ route('items.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Dodaj nowy przedmiot
        </a>
    </main>
@endsection
