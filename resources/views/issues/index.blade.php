@extends('layouts.app')

@section('content')
<div class="container mt-5">

    {{-- NAGŁÓWEK --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-handshake text-primary me-2"></i> Historia Wydań
            </h2>
            <div class="text-muted small mt-1">Rejestr wszystkich operacji wydania towaru</div>
        </div>

        <div>
            {{-- ZMIANA 1: Przycisk otwiera MODAL, zamiast przenosić na inną stronę --}}
            <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#createIssueModal">
                <i class="fas fa-plus me-2"></i> Wydaj Towar
            </button>
        </div>
    </div>


    @if($upcomingIssues->isNotEmpty())
        <div class="card border-warning mb-4 shadow-sm">
            <div class="card-header bg-warning bg-opacity-10 text-dark border-warning d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i> 
                    Wygasają w ciągu 30 dni ({{ $upcomingIssues->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Kiedy?</th>
                                <th>Pracownik</th>
                                <th>Produkt</th>
                                <th class="text-end pe-3">Pozostało</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingIssues as $issue)
                                @php
                                    $daysLeft = \Carbon\Carbon::now()->diffInDays($issue->due_date, false);
                                    $badgeColor = $daysLeft <= 7 ? 'bg-danger' : 'bg-warning text-dark';
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-bold text-dark">
                                        {{ $issue->due_date->format('Y-m-d') }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            {{ $issue->employee->last_name }} {{ $issue->employee->first_name }}
                                        </div>
                                    </td>
                                    <td>
                                        {{ $issue->batch->product->name }} 
                                        <span class="text-muted small">({{ $issue->batch->size }})</span>
                                    </td>
                                    <td class="text-end pe-3">
                                        <span class="badge {{ $badgeColor }}">
                                            @if($daysLeft < 0)
                                                Po terminie!
                                            @elseif($daysLeft == 0)
                                                Dzisiaj
                                            @else
                                                Za {{ ceil($daysLeft) }} dni
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- TABELA --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Data Wydania</th>
                        <th>Pracownik</th>
                        <th>Produkt</th>
                        <th>Data ważności / zwrotu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($issues as $issue)
                        <tr>
                            {{-- DATA --}}
                            <td class="ps-4 text-nowrap">
                                <div class="fw-bold text-dark">{{ $issue->issued_at->format('Y-m-d') }}</div>
                            </td>

                            {{-- PRACOWNIK --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <div class="fw-bold">
                                            {{ $issue->employee->last_name }} {{ $issue->employee->first_name }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ $issue->employee->position->name ?? 'Brak stanowiska' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- PRODUKT --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($issue->batch->product->preview_image_path)
                                        <img src="{{ Storage::url($issue->batch->product->preview_image_path) }}" 
                                             class="rounded border me-2" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded border d-flex align-items-center justify-content-center me-2 text-muted" 
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ route('items.show', $issue->batch->product->id) }}" class="text-decoration-none fw-bold text-dark">
                                            {{ $issue->batch->product->name }}  
                                        </a>
                                        <div class="small text-muted">Rozmiar: {{ $issue->batch->size }} | {{ $issue->quantity }} szt.</div>
                                    </div>
                                </div>
                            </td>

                            {{-- DATA WAŻNOŚCI --}}
                            <td> {{-- ZMIANA 2: Dodałem brakujące zamknięcie </td> --}}
                                {{ $issue->due_date?->format('Y-m-d') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 opacity-50"></i><br>
                                Brak historii wydań. Kliknij "Wydaj Towar", aby dodać pierwszy wpis.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACJA --}}
        <div class="card-footer bg-white border-0 py-3">
            {{ $issues->links() }}
        </div>
    </div>

    {{-- ZMIANA 3: Tutaj wrzucamy komponent Livewire (jako Modal) --}}
    <livewire:issues.issue-form />

</div>

{{-- ZMIANA 4: Skrypt do zamykania modala po zapisie --}}
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('close-modal', () => {
            const modalEl = document.getElementById('createIssueModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        });
    });
</script>

@endsection