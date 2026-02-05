@extends('layouts.app')

@section('title', 'Wydania')

@section('content')
    <main class="container mt-4">
        {{-- Sekcja powiadomień - zmieniona nazwa z $rentalsMonth na $expiringIssues --}}
        @if($expiringIssues->isNotEmpty())
            <div class="alert alert-warning">
                <h3 class="h5"><i class="fas fa-exclamation-triangle"></i> Koniec daty ważności w tym miesiącu</h3>
            </div>
            <table class="table table-hover mb-5">
                <thead class="bg-light">
                    <tr>
                        <th>Pracownik</th>
                        <th>Przedmiot</th>
                        <th>Ilość</th>
                        <th>Data wydania</th>
                        <th>Data wymiany</th>
                        <th class="text-end">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expiringIssues as $issue)
                    <tr>
                        {{-- Zmienione na first_name i last_name --}}
                        <td>{{ $issue->employee->first_name }} {{ $issue->employee->last_name }}</td>
                        {{-- Zmienione na name --}}
                        <td>{{ $issue->product->name }}</td>
                        {{-- Zmienione na quantity --}}
                        <td>{{ $issue->quantity }}</td>
                        {{-- Zmienione na issued_at --}}
                        <td>{{ $issue->issued_at->format('Y-m-d') }}</td>
                        {{-- Zmienione na due_date --}}
                        <td>{{ $issue->due_date ? $issue->due_date->format('Y-m-d') : '-' }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('issues.edit', $issue->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('issues.destroy', $issue->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Na pewno usunąć?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <h3>Lista wydań</h3>
        <table class="table table-hover">
            <thead class="bg-light">
                <tr>
                    <th>Pracownik</th>
                    <th>Przedmiot</th>
                    <th>Ilość</th>
                    <th>Data wydania</th>
                    <th>Data wymiany</th>
                    <th class="text-end">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($issues as $issue)
                <tr>
                    <td>{{ $issue->employee->first_name }} {{ $issue->employee->last_name }}</td>
                    <td>{{ $issue->product->name }}</td>
                    <td>{{ $issue->quantity }}</td>
                    <td>{{ $issue->issued_at->format('Y-m-d') }}</td>
                    <td>{{ $issue->due_date ? $issue->due_date->format('Y-m-d') : '-' }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <form action="{{ route('issues.destroy', $issue->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Na pewno usunąć?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
        </div>
    </main>
@endsection