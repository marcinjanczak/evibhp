@extends('layouts.app')

@section('content')
<div class="container mt-5">

    {{-- NAGŁÓWEK --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="fw-bold text-dark mb-0">
                <i class="fas fa-history text-primary me-2"></i> Historia Wydań
            </h2>
            <div class="text-muted small mt-1">Rejestr wszystkich operacji wydania towaru</div>
        </div>

        <div>
            <a href="{{ route('issues.create') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-hand-holding-box me-1"></i> Wydaj Towar
            </a>
        </div>
    </div>

    {{-- TABELA --}}
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Data Wydania</th>
                        <th>Pracownik</th>
                        <th>Produkt</th>
                        <th>Data końca obowiązynwania </th>
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
                                        <div class="small text-muted">Rozmiar: {{ $issue->batch->size }} {{ $issue->quantity }} sztuk</div>
                                        
                                        <div class="small text-muted">{{ $issue->batch->product->type }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                            {{ $issue->due_date?->format('Y-m-d') }}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
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
</div>
@endsection