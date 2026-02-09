@extends('layouts.app')

@section('title', 'Stanowiska')

@section('content')
<div class="container mt-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        
        <h2 class="fw-bold mb-0">Zarządzanie Stanowiskami</h2>
        
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary text-nowrap">
            <i class="fas fa-arrow-left me-1"></i> Pracownicy
        </a>
        
    </div>
    
    <livewire:positions.positions-table />

</div>

<div class="modal fade" id="positionModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Stanowisko i Wyposażenie</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <livewire:positions.position-form />
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('positionModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => {
                Livewire.dispatch('reset-position-form');
            });
        }
    });
</script>
@endsection