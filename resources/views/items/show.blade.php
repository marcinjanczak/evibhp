@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header site-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Szczegóły przedmiotu: {{ $item->nazwa }}</h3>
                        <a href="{{ route('items.index') }}" class="btn btn-primary link-box">
                            <i class="fas fa-arrow-left"></i> Powrót do listy
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if ($item->zdjecie_pogladowe_path)
                                <img src="{{ Storage::url($item->zdjecie_pogladowe_path) }}" 
                                     alt="Zdjęcie przedmiotu {{ $item->nazwa }}"
                                     class="img-fluid rounded shadow">
                            @else
                                <img src="{{ asset('images/default_item_image.png') }}"
                                     alt="Brak zdjęcia"
                                     class="img-fluid rounded shadow">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Nazwa:</strong> {{ $item->nazwa }}</li>
                                <li class="list-group-item"><strong>Typ:</strong> {{ $item->typ }}</li>
                                <li class="list-group-item"><strong>Rozmiar:</strong> {{ $item->rozmiar }}</li>
                                <li class="list-group-item"><strong>Ilość:</strong> {{ $item->ilosc_dodanych }}</li>
                                <li class="list-group-item"><strong>Data ważności:</strong> {{ $item->data_waznosci ?? 'Brak danych' }}</li>
                                @if ($item->faktura_pdf_path)
                                    <li class="list-group-item">
                                        <strong>Faktura:</strong> <a href="{{ Storage::url($item->faktura_pdf_path) }}" target="_blank">Pokaż plik</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
      @if (!$rentals->isEmpty() )
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
                            {{-- <a href="{{ route('rentals.edit', $rental->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit"></i> Edytuj
                            </a>
                            <form action="{{ route('rentals.destroy', $rental->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Na pewno usunąć?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form> --}}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
</div>
@endsection
