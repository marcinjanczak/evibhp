@extends('layouts.app')

@section('content')
<div class="container mt-4">
    
    {{-- NAGŁÓWEK --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
        <div>
            <h2 class="fw-bold mb-0">
                <i class="fas fa-id-badge text-primary me-2"></i> {{ $position->name }}
            </h2>
            <div class="text-muted small mt-1">Szczegóły stanowiska i wymagane wyposażenie</div>
        </div>
        <div>
            <a href="{{ route('positions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Wróć do listy
            </a>
        </div>
    </div>

    <div class="row">
        
        {{-- KOLUMNA LEWA: PRACOWNICY --}}
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-users text-secondary me-2"></i> Przypisani Pracownicy</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Pracownik</th>
                                <th>Email</th>
                                <th class="text-end pe-4">Profil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($position->employees as $employee)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2 fw-bold" 
                                                 style="width: 35px; height: 35px; font-size: 0.8rem;">
                                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                            </div>
                                            <span class="fw-bold">{{ $employee->last_name }} {{ $employee->first_name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted small">{{ $employee->email }}</td>
                                    <td class="text-end pe-4">
                                        {{-- Link do podglądu pracownika (jeśli kiedyś zrobisz taki widok) --}}
                                        <a href="#" class="btn btn-sm btn-light text-primary"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        Brak pracowników przypisanych do tego stanowiska.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- KOLUMNA PRAWA: SUGEROWANY SPRZĘT --}}
        <div class="col-md-5">
            <div class="card shadow-sm border-0 border-top border-3 border-success">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-success"><i class="fas fa-tools me-2"></i> Sugerowane Wyposażenie</h5>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($position->products as $product)
                        <div class="list-group-item d-flex align-items-center py-3">
                            {{-- Zdjęcie produktu --}}
                            <div class="me-3">
                                @if($product->preview_image_path)
                                    <img src="{{ Storage::url($product->preview_image_path) }}" 
                                         class="rounded border" 
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded border d-flex align-items-center justify-content-center text-muted" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Dane produktu --}}
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold">
                                    <a href="{{ route('items.show', $product->id) }}" class="text-decoration-none text-dark stretched-link">
                                        {{ $product->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $product->type }}</small>
                            </div>

                            {{-- Dostępność (suma stanów ze wszystkich partii) --}}
                            <div class="text-end">
                                @php $stock = $product->batches->sum('current_quantity'); @endphp
                                @if($stock > 0)
                                    <span class="badge bg-success rounded-pill">{{ $stock }} szt.</span>
                                @else
                                    <span class="badge bg-danger rounded-pill">Brak</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted">
                            <i class="fas fa-clipboard-check fa-2x mb-2 opacity-50"></i><br>
                            Brak zdefiniowanego wyposażenia dla tego stanowiska.
                        </div>
                    @endforelse
                </div>
                @if($position->products->isNotEmpty())
                    <div class="card-footer bg-light text-muted small text-center">
                        To wyposażenie będzie podpowiadane przy wydawaniu towaru.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection