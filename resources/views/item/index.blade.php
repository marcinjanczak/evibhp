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
                <th class="text-end">Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->id}}</td>
                <td>{{ $item->nazwa}}</td>
                <td>{{ $item->typ}}</td>
                <td>{{ $item->rozmiar}}</td>
                <td>Ilosc</td>
                <td>Edytuj</td>

            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('items.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Dodaj nowy przedmiot
    </a>
</main>


@endsection