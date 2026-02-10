@extends('layouts.app')

@section('title', 'Szczegóły pracownika')

@section('content')
    <div class="container mt-5">
    
    {{-- 1. GÓRNA BELKA (NAWIGACJA) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('employees.index') }}" class="text-decoration-none text-secondary mb-2 d-inline-block">
                <i class="fas fa-arrow-left me-1"></i> Powrót do listy
            </a>
            <h2 class="fw-bold mb-0">Karta Pracownika</h2>
        </div>
        <div>
            {{-- Przycisk edycji pracownika (opcjonalnie) --}}
            <button class="btn btn-outline-primary me-2">
                <i class="fas fa-edit"></i> Edytuj dane
            </button>
            {{-- Przycisk do wydania towaru TEMU pracownikowi --}}
            {{-- Możesz tu podpiąć link do formularza z pre-selekcją ID --}}
            <button class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Wydaj towar
            </button>
        </div>
    </div>

    <div class="row g-4">
        
        {{-- 2. LEWA KOLUMNA: WIZYTÓWKA --}}
        <div class="col-md-4 col-xl-3">
            <div class="card shadow-sm border-0 text-center p-4 h-100">
                <div class="card-body">
                    <h4 class="fw-bold mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                    <p class="text-muted mb-3">{{ $employee->position->name ?? 'Brak stanowiska' }}</p>
                    <div class="bg-light rounded p-2 border">
                        <div class="h4 fw-bold text-primary">{{ $issues->count() }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">PRZEDMIOTÓW</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. PRAWA KOLUMNA: TABELA WYPOSAŻENIA --}}
        <div class="col-md-8 col-xl-9">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-boxes me-2 text-secondary"></i> Aktualne Wyposażenie</h5>
                    
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary small text-uppercase">
                            <tr>
                                <th class="ps-4">Produkt</th>
                                <th>Partia / Rozmiar</th>
                                <th>Data Wydania</th>
                                <th>Ważność / Zwrot</th>
                                <th class="text-end pe-4">Akcja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($issues as $issue)
                                @php
                                    $isOverdue = $issue->due_date && $issue->due_date->isPast();
                                    $isDueSoon = $issue->due_date && $issue->due_date->diffInDays(now()) < 30 && !$isOverdue;
                                @endphp
                                <tr class="{{ $isOverdue ? 'bg-danger bg-opacity-10' : '' }}">
                                    
                                    {{-- PRODUKT --}}
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white border rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">

                                                 <img src="{{ Storage::url($issue->batch->product->preview_image_path) }}"
                                                    class="rounded-3 shadow-sm"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $issue->batch->product->name }}</div>
                                                <div class="small text-muted">{{ $issue->batch->product->type }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- PARTIA --}}
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $issue->batch->size }}
                                        </span>
                                        <div class="small text-muted mt-1">Ilość: {{ $issue->quantity }} szt.</div>
                                    </td>

                                    {{-- DATA WYDANIA --}}
                                    <td>
                                        {{ $issue->created_at->format('Y-m-d') }}
                                    </td>

                                    {{-- DATA WAŻNOŚCI (KOLOROWANA) --}}
                                    <td>
                                        @if($issue->due_date)
                                            @if($isOverdue)
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i> 
                                                    {{ $issue->due_date->format('Y-m-d') }}
                                                </span>
                                                <div class="text-danger small fw-bold mt-1">Po terminie!</div>
                                            @elseif($isDueSoon)
                                                <span class="badge bg-warning text-dark">
                                                    {{ $issue->due_date->format('Y-m-d') }}
                                                </span>
                                                <div class="text-warning text-dark small mt-1">Wygasa wkrótce</div>
                                            @else
                                                <span class="badge bg-success bg-opacity-25 text-success border border-success">
                                                    {{ $issue->due_date->format('Y-m-d') }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- AKCJE (NP. ZWROT) --}}
                                    <td class="text-end pe-4">
                                        {{-- Tutaj w przyszłości podepniemy funkcję zwrotu --}}
                                        <form action="{{ route('issues.archive', $issue->id) }}" method="POST" 
                                            onsubmit="return confirm('Czy na pewno chcesz zarchiwizować ten przedmiot?');">
                                            @csrf
                                            
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Zwróć towar">
                                                <i class="fas fa-undo"></i> Zarchiwizuj
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-check-circle fa-3x mb-3 text-success opacity-25"></i>
                                        <p>Pracownik nie posiada obecnie żadnego wyposażenia.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection