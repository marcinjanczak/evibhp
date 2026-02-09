@extends('layouts.app')

@section('title', 'Lista Przedmiotów')

@section('content')
<div class="container mt-5"> {{-- Zmieniłem mt-4 na mt-5, żeby było równo z innymi podstronami --}}
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Usunąłem text-primary, dodałem ikonkę i mb-0 --}}
        <h2 class="fw-bold mb-0">
            <i class="fas fa-boxes text-primary me-2"></i> Zarządzanie Produktami

        </h2>

    </div>

    <livewire:products-components.product-table />

</div>
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addProductModalLabel">
                    <i class="fas fa-box"></i> Definiowanie nowego produktu
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <livewire:products-components.product-form />

            </div>
        </div>
    </div>
</div>
@endsection

<script>
    const myModal = document.getElementById('addProductModal');

    myModal.addEventListener('hidden.bs.modal', () => {
        Livewire.dispatch('reset-product-form');
    });
</script>