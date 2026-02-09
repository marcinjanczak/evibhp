@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 fw-bold">Baza Pracowników</h2>
    
    <livewire:employees.employee-table />

</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus"></i> Nowy pracownik</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <livewire:employees.employee-form />

            </div>
        </div>
    </div>
</div>

@endsection