@extends('layouts.app')

@section('title', 'Szczegóły przedmiotu')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0 text-primary">Szczegóły przedmiotu: {{ $item->name }}</h3>
                            <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Powrót do listy
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                @if ($item->preview_image_path)
                                    <img src="{{ Storage::url($item->preview_image_path) }}"
                                        alt="{{ $item->name }}" class="img-fluid rounded shadow-sm border">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded border" style="height: 250px;">
                                        <i class="fas fa-box fa-4x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Nazwa:</strong> {{ $item->name }}</li>
                                    <li class="list-group-item"><strong>Typ:</strong> {{ $item->type }}</li>
                                    <li class="list-group-item"><strong>Rozmiar:</strong> <span class="badge bg-secondary">{{ $item->size }}</span></li>
                                    <li class="list-group-item"><strong>Łącznie dodano:</strong> {{ $item->quantity_added }} szt.</li>
                                    <li class="list-group-item">
                                        <strong>Ilość na magazynie:</strong> 
                                        <span class="badge {{ ($item->inventory->quantity ?? 0) > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->inventory->quantity ?? 0 }} szt.
                                        </span>
                                    </li>
                                    <li class="list-group-item"><strong>Data ważności:</strong> {{ $item->expiration_date ? $item->expiration_date->format('Y-m-d') : 'Brak' }}</li>
                                    @if ($item->invoice_pdf_path)
                                        <li class="list-group-item">
                                            <strong>Dokumentacja:</strong> 
                                            <a href="{{ Storage::url($item->invoice_pdf_path) }}" class="btn btn-sm btn-outline-danger ms-2" target="_blank">
                                                <i class="fas fa-file-pdf"></i> Pokaż fakturę
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <hr class="my-5">

                        @if ($rentals->isNotEmpty())
                            <div class="mt-4">
                                <h4 class="mb-3"><i class="fas fa-history text-muted"></i> Aktualne wydania tego przedmiotu</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Pracownik</th>
                                                <th>Ilość</th>
                                                <th>Data wydania</th>
                                                <th>Termin wymiany</th>
                                                <th class="text-end">Akcje</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($rentals as $issue)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('employees.show', $issue->employee->id) }}" class="text-decoration-none fw-bold">
                                                            {{ $issue->employee->first_name }} {{ $issue->employee->last_name }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $issue->quantity }} szt.</td>
                                                    <td>{{ $issue->issued_at->format('Y-m-d') }}</td>
                                                    <td>
                                                        @if($issue->due_date)
                                                            <span class="{{ $issue->due_date->isPast() ? 'text-danger fw-bold' : '' }}">
                                                                {{ $issue->due_date->format('Y-m-d') }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <form action="{{ route('issues.destroy', $issue->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Czy na pewno chcesz usunąć to wydanie?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-light border text-center py-4">
                                <p class="mb-0 text-muted">Brak zarejestrowanych wydań dla tego przedmiotu.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection