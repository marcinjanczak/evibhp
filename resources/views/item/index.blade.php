@extends('layouts.app')
 
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Lista przedmiotów</h3>
                        <a href="{{ route('items.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Dodaj nowy przedmiot
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nazwa</th>
                                    <th>Typ</th>
                                    <th>Rozmiar</th>
                                    <th>Ilość</th>
                                    <th width="200px">Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($przedmioty as $przedmiot)
                                <tr>
                                    <td>{{ $przedmiot->IdPrzedmiot }}</td>
                                    <td>{{ $przedmiot->Nazwa }}</td>
                                    <td>{{ $przedmiot->Typ }}</td>
                                    <td>{{ $przedmiot->Rozmiar }}</td>
                                    <td>{{ $przedmiot->Ilosc ?? 0 }}</td>
                                    <td>
                                        <a href="{{ route('items.edit', $przedmiot->IdPrzedmiot) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edytuj
                                        </a>
                                        <form action="{{ route('items.destroy', $przedmiot->IdPrzedmiot) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć ten przedmiot?')">
                                                <i class="fas fa-trash"></i> Usuń
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
 