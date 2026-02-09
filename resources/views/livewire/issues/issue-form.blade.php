<div class="modal fade" id="createIssueModal" tabindex="-1" aria-labelledby="createIssueModalLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createIssueModalLabel">
                    <i class="fas fa-hand-holding-box me-2"></i> Wydawanie Towaru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body bg-light">
                <form wire:submit="save">
                    
                    {{-- === SEKCJA 1: WYBÓR PRACOWNIKA === --}}
                    <div class="card p-3 mb-3 shadow-sm border-0">
                        <label class="form-label fw-bold">1. Wybierz pracownika:</label>
                        
                        {{-- Wyszukiwarka Pracownika --}}
                        <input type="text" 
                               class="form-control mb-2" 
                               placeholder="Szukaj (imię, nazwisko)..." 
                               wire:model.live.debounce.300ms="searchEmployee">

                        {{-- Tabela Pracowników --}}
                        <div class="table-responsive border rounded bg-white" style="max-height: 200px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <thead class="table-light sticky-top">
                                    <tr>
                                        <th>Imię i Nazwisko</th>
                                        <th>Stanowisko</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employees as $emp)
                                        <tr wire:click="selectEmployee({{ $emp->id }})" 
                                            class="cursor-pointer {{ $selectedEmployeeId == $emp->id ? 'table-primary border-primary' : '' }}" 
                                            style="cursor: pointer;">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    {{ $emp->last_name}} {{ $emp->first_name }} 
                                                </div>
                                            </td>
                                            <td>{{ $emp->position->name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-3">Brak wyników...</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @error('employee_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    {{-- === SEKCJA 2: WYBÓR PRODUKTU (Pokazuje się po wybraniu pracownika) === --}}
                    @if($employee_id)
                        <div class="card p-3 mb-3 shadow-sm border-0 animate__animated animate__fadeIn">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold mb-0">2. Wybierz produkt:</label>
                                
                                {{-- Wyszukiwarka Produktu --}}
                                <div class="input-group input-group-sm" style="max-width: 250px;">
                                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Szukaj produktu..." wire:model.live.debounce.300ms="searchProduct">
                                </div>
                            </div>

                            {{-- Lista Produktów (Pionowa) --}}
                            <div class="d-flex flex-column gap-2" style="max-height: 300px; overflow-y: auto;">
                                @php
                                    $sortedProducts = $products->sortByDesc(function($prod) use ($suggestedProductIds) {
                                        return in_array($prod->id, $suggestedProductIds);
                                    });
                                @endphp

                                @forelse($sortedProducts as $prod)
                                    @php
                                        $isSuggested = in_array($prod->id, $suggestedProductIds);
                                        $isSelected = $product_id == $prod->id;
                                        $totalStock = $prod->batches ? $prod->batches->sum('current_quantity') : 0;
                                        $hasStock = $totalStock > 0;
                                    @endphp

                                    <div wire:click="selectProduct({{ $prod->id }})" 
                                         class="card border-0 shadow-sm p-2 transition-hover
                                         {{ $isSelected ? 'ring-2 ring-primary bg-primary bg-opacity-10' : 'bg-white' }}
                                         {{ !$hasStock ? 'opacity-50 grayscale' : '' }}"
                                         style="cursor: pointer; transition: all 0.2s ease;">
                                        
                                        <div class="d-flex align-items-center justify-content-between">
                                            {{-- Lewa strona --}}
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="fw-bold">{{ $prod->name }}</div>
                                                <small class="text-muted">({{ $prod->type }})</small>
                                                @if($isSuggested)
                                                    <span class="badge bg-warning text-dark small"><i class="fas fa-star"></i> Sugerowany</span>
                                                @endif
                                            </div>

                                            {{-- Prawa strona --}}
                                            <div class="d-flex align-items-center gap-3">
                                                @if($hasStock)
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill">{{ $totalStock }} szt.</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">Brak</span>
                                                @endif

                                                @if($isSelected)
                                                    <i class="fas fa-check-circle text-primary fs-5"></i>
                                                @else
                                                    <i class="far fa-circle text-muted opacity-25 fs-5"></i>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-3 text-muted">Brak produktów.</div>
                                @endforelse
                            </div>
                            @error('product_id') <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    {{-- === SEKCJA 3: SZCZEGÓŁY WYDANIA (Partia, Ilość, Data) === --}}
                    @if($product_id)
                        <div class="card p-3 mb-3 shadow-sm border-0 animate__animated animate__fadeIn">
                            <label class="form-label fw-bold">3. Szczegóły wydania:</label>
                            
                            <div class="row g-3">
                                {{-- Wybór Partii --}}
                                <div class="col-md-12">
                                    <label class="form-label small text-muted">Dostępna Partia (Rozmiar)</label>
                                    <select wire:model="batch_id" class="form-select">
                                        <option value="">-- Wybierz rozmiar / partię --</option>
                                        @forelse($batches as $batch)
                                            <option value="{{ $batch->id }}">
                                                Rozmiar: {{ $batch->size }} | Ilość: {{ $batch->current_quantity }} szt. | Ważność: {{ $batch->expiration_date->format('Y-m-d') }}
                                            </option>
                                        @empty
                                            <option disabled>Brak towaru na stanie!</option>
                                        @endforelse
                                    </select>
                                    @error('batch_id') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                {{-- Ilość --}}
                                <div class="col-md-4">
                                    <label class="form-label small text-muted">Ilość</label>
                                    <input type="number" wire:model="quantity" class="form-control" min="1">
                                    @error('quantity') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                {{-- Data Zwrotu + Przyciski --}}
                                <div class="col-md-8">
                                    <label class="form-label small text-muted">Data zwrotu / wymiany</label>
                                    
                                    {{-- Szybkie przyciski miesięcy --}}
                                    <div class="btn-group w-100 mb-2" role="group">
                                        @foreach([6, 12, 24, 36] as $months)
                                            <button type="button" 
                                                    class="btn btn-sm {{ $selectedMonths == $months ? 'btn-primary' : 'btn-outline-secondary' }}" 
                                                    wire:click="setPeriod({{ $months }})">
                                                +{{ $months }} mies.
                                            </button>
                                        @endforeach
                                    </div>

                                    {{-- Input daty --}}
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="fas fa-calendar-day"></i></span>
                                        <input type="date" wire:model="due_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </form>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                
                {{-- Przycisk Zapisz i Dodaj Kolejny --}}
                <button type="button" wire:click="save(true)" class="btn btn-primary">
                    <span wire:loading.remove wire:target="save(true)">
                        <i class="fas fa-plus-circle me-1"></i> Zapisz i kolejny
                    </span>
                    <span wire:loading wire:target="save(true)">Zapisywanie...</span>
                </button>

                {{-- Przycisk Zapisz i Zamknij --}}
                <button type="button" wire:click="save(false)" class="btn btn-success">
                    <span wire:loading.remove wire:target="save(false)">
                        <i class="fas fa-check me-1"></i> Zapisz i zamknij
                    </span>
                    <span wire:loading wire:target="save(false)">Przetwarzanie...</span>
                </button>
            </div>

        </div>
    </div>
</div>