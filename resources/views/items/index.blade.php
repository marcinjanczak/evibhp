@extends('layouts.app')

@section('content')
    <main>
        <h2>Lista przedmiotów</h2>
        <table class="table table-hover">
            <thead class="bg-light">
                <tr>
                    <th>Zdjęcie poglądowe</th>
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
                        <td>
                            @if ($item->zdjecie_pogladowe_path)
                                <img src="{{ Storage::url($item->zdjecie_pogladowe_path) }}"
                                    alt="Zdjęcie przedmiotu {{ $item->nazwa }}" class="img-thumbnail"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/default_item_image.png') }}" alt="Brak zdjęcia"
                                    class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                            @endif
                        </td>
                        <td>{{ $item->nazwa }}</td>
                        <td>{{ $item->typ }}</td>
                        <td>{{ $item->rozmiar }}</td>
                        <td>{{ $item->ilosc_dodanych }}</td>
                        <td>{{ $item->data_waznosci }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i> Szczegóły
                                </a>
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
