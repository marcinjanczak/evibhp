@extends('layouts.app')

@section('content')
<div class="container mt-5">

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

    <livewire:issues.issues-table />

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