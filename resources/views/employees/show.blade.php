@extends('layouts.app')

@section('title', 'Szczegóły pracownika')

@section('content')
    <div>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('employees.index') }}" class="text-decoration-none text-secondary mb-2 d-inline-block">
                <i class="fas fa-arrow-left me-1"></i> Powrót do listy
            </a>
            <h2 class="fw-bold mb-0">Karta Pracownika</h2>
        </div>
        <div> 

            <button class="btn btn-success shadow-sm" 
                    onclick="openIssueModalForEmployee('{{ $employee->id }}')">
                <i class="fas fa-plus-circle me-1"></i> Wydaj towar
            </button>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-md-4 col-xl-3">
            <div class="card shadow-sm border-0 text-center p-4 h-100">
                <div class="card-body">
                    <h4 class="fw-bold mb-1">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                    <livewire:employees.assign-position :employee="$employee" />
                    <div class="bg-light rounded p-2 border">
                        <div class="h4 fw-bold text-primary">{{ $issues->count() }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">PRZEDMIOTÓW</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 col-xl-9">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-boxes me-2 text-secondary"></i> Aktualne Wyposażenie</h5>
                    
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="ps-4">Produkt</th>
                                <th>Data Wydania</th>
                                <th>Ważność / Zwrot</th>
                                <th class="text-end pe-4">Akcja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($issues as $issue)
                              @php
                                    $today = \Carbon\Carbon::now()->startOfDay();
                                    $dueDate = $issue->due_date->startOfDay();
                                    
                                    $warningLimit = $today->copy()->addDays(30);

                                    $isOverdue = $dueDate->lt($today);

                                    $isDueSoon = !$isOverdue && $dueDate->lte($warningLimit);
                                @endphp
                                <tr class="{{ $isOverdue ? 'bg-danger bg-opacity-10' : '' }}">
                                    
                                    {{-- PRODUKT --}}
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-white border rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 40px; height: 40px;">

                                                 <img src="{{ $issue->batch->product->image_url }}"
                                                    class="rounded-3 shadow-sm"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $issue->batch->product->name }}</div>
                                                <div class="small text-muted">Rozmiar: {{ $issue->batch->size }}</div>
                                                <div class="small text-muted">Ilość: {{ $issue->quantity }} szt.</div>
                                            </div>
                                        </div>
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

<livewire:issues.issue-form />
<script>
    function openIssueModalForEmployee(employeeId) {
        // 1. Wyślij zdarzenie do Livewire
        // Sprawdzamy czy Livewire jest dostępny, żeby nie sypało błędami
        if (typeof Livewire !== 'undefined') {
            // Uwaga: używamy window.Livewire dla pewności
            window.Livewire.dispatch('set-employee-for-modal', { id: employeeId });
        } else {
            console.error('Błąd: Livewire nie został załadowany.');
        }

        // 2. Znajdź element modala w DOM
        const modalElement = document.getElementById('createIssueModal');

        if (modalElement) {
            // 3. TO JEST KLUCZ DO NAPRAWY BŁĘDU:
            // Zamiast "new bootstrap.Modal(...)", używamy "getOrCreateInstance"
            // To zapobiega tworzeniu duplikatów i błędów z konfiguracją (backdrop).
            const myModal = bootstrap.Modal.getOrCreateInstance(modalElement, {
                backdrop: 'static', // Opcjonalnie: wymuś tło
                keyboard: false     // Opcjonalnie: wyłącz zamykanie ESC
            });
            
            myModal.show();
        } else {
            console.error('Krytyczny błąd: Nie znaleziono modala o ID "createIssueModal" w kodzie HTML.');
        }
    }
</script>
@endsection
