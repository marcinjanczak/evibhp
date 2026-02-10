@extends('layouts.app')

@section('content')
<div class="container mt-5">
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-users text-primary me-2"></i> Baza Pracowników
            </h2>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('positions.index') }}" class="btn btn-outline-primary shadow-sm">
                <i class="fas fa-briefcase me-1"></i> Zarządzaj Stanowiskami
            </a>
        </div>
    </div>
      
          
    
    <livewire:employees.employee-table />

</div>

<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
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