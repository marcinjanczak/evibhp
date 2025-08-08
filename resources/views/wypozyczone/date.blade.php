@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header site-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-white">Archiwum wypożyczeń</h3>
                        <a href="{{ route('wypozyczone.index') }}" class="btn btn-primary link-box">
                            <i class="fas fa-arrow-left"></i> Powrót
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pracownik</th>
                                    <th>Przedmiot</th>
                                    <th>Ilość</th>
                                    <th>Data wypożyczenia</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($archiwalne as $wypozyczenie)
                                <tr>
                                    <td>{{ $wypozyczenie->pracownik->imie }} {{ $wypozyczenie->pracownik->nazwisko }}</td>
                                    <td>{{ $wypozyczenie->przedmiot->Nazwa }}</td>
                                    <td>{{ $wypozyczenie->Ilosc }}</td>
                                    <td>{{ \Carbon\Carbon::parse($wypozyczenie->Data)->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">Archiwalne</span>
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
</div>
@endsection