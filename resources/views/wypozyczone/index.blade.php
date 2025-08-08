@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header site-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-white">Aktualne wypożyczenia</h3>
                        <div>
                            <a href="{{ route('wypozyczone.date') }}" class="btn btn-info link-box me-2">
                                <i class="fas fa-history"></i> Archiwalne
                            </a>
                            <a href="{{ route('wypozyczone.create') }}" class="btn btn-success link-box">
                                <i class="fas fa-plus"></i> Nowe wypożyczenie
                            </a>
                        </div>
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
                                    <th>Data do zwrotu</th>
                                    <th width="250px">Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wypozyczenia as $wypozyczenie)
                                <tr>
                                    <td>
                                           {{ $wypozyczenie->pracownik->imie }} {{ $wypozyczenie->pracownik->nazwisko }}
                                    </td>
                                    <td>{{ optional($wypozyczenie->przedmiot)->Nazwa ?? 'Brak' }}</td>
                                    <td>{{ $wypozyczenie->Ilosc }}</td>
                                    <td>{{ \Carbon\Carbon::parse($wypozyczenie->Data)->format('d.m.Y H:i') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($wypozyczenie->DataDoZwrotu)->format('d.m.Y H:i') }}</td>

                                    <td class="links-container">
                                        <a href="{{ route('wypozyczone.edit', $wypozyczenie->IdWypozyczenia) }}" 
                                           class="btn btn-primary btn-sm links-vievs">
                                            <i class="fas fa-edit"></i> Edytuj
                                        </a>
                                        <form action="{{ route('wypozyczone.destroy', $wypozyczenie->IdWypozyczenia) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm links-vievs" 
                                                    onclick="return confirm('Czy na pewno chcesz usunąć to wypożyczenie?')">
                                                <i class="fas fa-trash"></i> Usuń
                                            </button>
                                        </form>
                                        <form action="{{ route('wypozyczone.return', $wypozyczenie->IdWypozyczenia) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm links-vievs" 
                                                    onclick="return confirm('Zakończyć wypożyczenie?')">
                                                <i class="fas fa-check"></i> Zakończ
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
</div>
@endsection
