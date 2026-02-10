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
            <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#createIssueModal">
                <i class="fas fa-plus me-2"></i> Wydaj Towar
            </button>
        </div>
    </div>

    {{-- SEKCJA POWIADOMIEŃ (Zostawiamy tutaj, bo to osobna logika) --}}
    @if($upcomingIssues->isNotEmpty())
        <div class="card border-warning mb-4 shadow-sm">
             {{-- ... (Twój kod sekcji powiadomień bez zmian) ... --}}
             {{-- Skróciłem dla czytelności, tutaj zostaje to co miałeś --}}
             <div class="card-header bg-warning bg-opacity-10 text-dark border-warning">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-exclamation-triangle me-2 text-warning"></i> 
                    Wygasają w ciągu 30 dni ({{ $upcomingIssues->count() }})
                </h5>
             </div>
             {{-- ... reszta tabeli upcomingIssues ... --}}
        </div>
    @endif

    {{-- NOWY KOMPONENT TABELI --}}
    <livewire:issues.issues-table />

    {{-- KOMPONENT FORMULARZA (MODAL) --}}
    <livewire:issues.issue-form />

</div>

{{-- SKRYPT --}}
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