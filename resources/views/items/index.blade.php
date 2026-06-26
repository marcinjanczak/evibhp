@extends('layouts.app')

@section('title', 'Lista Przedmiotów')

@section('content')
{{-- Dodałem klasę 'pb-5' (Padding Bottom 5) oraz 'min-vh-100' dla pewności --}}
<div> 
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-boxes text-primary me-2"></i> Zarządzanie Przdemiotami
        </h2>

    </div>

    {{-- Tabela Livewire --}}
    <livewire:products-components.product-table />

</div>

{{-- MODAL - Najlepiej trzymać go poza głównym kontenerem, ale w sekcji content jest OK --}}
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="fas fa-box me-2"></i> Definiowanie nowego produktu
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <livewire:products-components.product-form />
            </div>
        </div>
    </div>
</div>

{{-- SKRYPTY - Najlepiej używać @push, jeśli masz @stack('scripts') w layoutcie --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const myModal = document.getElementById('addProductModal');
        if (myModal) {
            myModal.addEventListener('hidden.bs.modal', () => {
                Livewire.dispatch('reset-product-form');
            });
        }
    });
</script>
@endpush

@endsection
