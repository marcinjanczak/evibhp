@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            @if($item->preview_image_path)
                <img src="{{ Storage::url($item->preview_image_path) }}" class="rounded me-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
            @endif
            <div>
                <h2 class="mb-0">{{ $item->name }}</h2>
                <div class="text-muted">{{ $item->type }}</div>
            </div>
        </div>
        
        <div class="text-end">
            <a href="{{ route('items.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fas fa-arrow-left me-1"></i> Wróć do listy przedmiotów
            </a>
           

          
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-3 border-bottom pb-2">Dostępne Partie na magazynie</h4>
            <div class="text-end">
             <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#addBatchModal">
                <i class="fas fa-plus"></i> Przyjmij dostawę
            </button>
            </div>
            
            <table class="table table-hover align-middle bg-white shadow-sm rounded">
                <thead class="table-light">
                    <tr>
                        <th>Rozmiar</th>
                        <th>Stan</th>
                        <th>Ważność</th>
                        <th>Faktura</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($item->batches as $batch)
                        <tr class="{{ $batch->current_quantity == 0 ? 'table-secondary text-muted' : '' }}">
                            <td><span class="badge bg-secondary">{{ $batch->size }}</span></td>
                            <td>
                                <strong>{{ $batch->current_quantity }}</strong> 
                                <span class="text-muted small">/ {{ $batch->initial_quantity }}</span>
                            </td>
                            <td>
                                @if($batch->expiration_date->isPast())
                                    <span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> {{ $batch->expiration_date->format('Y-m-d') }}</span>
                                @else
                                    {{ $batch->expiration_date->format('Y-m-d') }}
                                @endif
                            </td>
                            <td>
                                @if($batch->invoice_pdf_path)
                                    <a href="{{ Storage::url($batch->invoice_pdf_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-box-open fa-2x mb-2"></i><br>
                                Brak towaru na stanie. Kliknij przycisk u góry, aby przyjąć dostawę.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addBatchModal" tabindex="-1" aria-labelledby="addBatchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addBatchModalLabel">Przyjęcie nowej dostawy</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <livewire:products-components.batch-form :product="$item" />

            </div>
        </div>
    </div>
</div>
@endsection