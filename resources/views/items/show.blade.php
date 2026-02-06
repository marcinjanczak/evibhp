@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    {{-- Nagłówek Produktu --}}
    <div class="d-flex align-items-center mb-4">
        @if($item->preview_image_path)
            <img src="{{ Storage::url($item->preview_image_path) }}" class="rounded me-3 shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
        @endif
        <div>
            <h2 class="mb-0">{{ $item->name }}</h2>
            <span class="text-muted">{{ $item->type }}</span>
            <span class="badge bg-primary ms-2">Łączny stan: {{ $item->total_stock }} szt.</span>
        </div>
    </div>

    <div class="row">
        {{-- LEWA KOLUMNA: Lista Partii --}}
        <div class="col-md-8">
            <livewire:products-components.batch-form :product="$item" />

            <h4 class="mb-3">Dostępne Partie na magazynie</h4>
            
            <table class="table table-bordered bg-white shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Rozmiar</th>
                        <th>Ilość</th>
                        <th>Ważność</th>
                        <th>Nr Partii</th>
                        <th>Faktura</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($item->batches as $batch)
                        <tr class="{{ $batch->current_quantity == 0 ? 'text-muted bg-light' : '' }}">
                            <td class="fw-bold">{{ $batch->size }}</td>
                            <td>{{ $batch->current_quantity }} / {{ $batch->initial_quantity }}</td>
                            <td>{{ $batch->expiration_date->format('Y-m-d') }}</td>
                            <td>{{ $batch->batch_number ?? '-' }}</td>
                            <td>
                                @if($batch->invoice_pdf_path)
                                    <a href="{{ Storage::url($batch->invoice_pdf_path) }}" target="_blank" class="text-danger">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Brak towaru na stanie. Dodaj pierwszą dostawę poniżej.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection