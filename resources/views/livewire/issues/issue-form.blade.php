<div>
    <form wire:submit="save">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-hand-holding-box me-2"></i> Wydawanie Towaru</h5>
            </div>
            <div class="card-body">
               
                <div class="card p-3 mb-3">
                <label class="form-label fw-bold">Wybierz pracownika:</label>
                
                <input type="text" 
                    class="form-control mb-2" 
                    placeholder="Szukaj (imię, nazwisko)..." 
                    wire:model.live.debounce.300ms="searchEmployee">

                <div class="table-responsive border rounded" style="max-height: 250px; overflow-y: auto;">
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
                                    <td colspan="3" class="text-center text-muted py-3">Brak wyników...</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

               


                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h6 class="mb-0 fw-bold text-uppercase text-muted ls-1">
                                <i class="fas fa-boxes me-2"></i> Wybierz przedmiot
                            </h6>
                            
                            {{-- Wyszukiwarka --}}
                            <div class="input-group" style="max-width: 300px;">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" 
                                    class="form-control bg-light border-start-0" 
                                    placeholder="Szukaj produktu..." 
                                    wire:model.live.debounce.300ms="searchProduct">
                            </div>
                        </div>
                    </div>

                <div class="d-flex flex-column gap-2" style="max-height: 500px; overflow-y: auto; padding: 5px;">
                    
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
                            class="card border-0 shadow-sm p-3 transition-hover
                            {{ $isSelected ? 'ring-2 ring-primary bg-primary bg-opacity-10' : 'bg-white' }}
                            {{ !$hasStock ? 'opacity-50 grayscale' : '' }}"
                            style="cursor: pointer; transition: all 0.2s ease; {{ $isSelected ? 'transform: scale(1.01);' : '' }}">
                            
                            <div class="d-flex align-items-center justify-content-between">
                                
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h6 class="mb-0 fw-bold text-dark">{{ $prod->name }}</h6>
                                            
                                            @if($isSuggested)
                                                <span class="badge bg-warning text-dark small py-1">
                                                    <i class="fas fa-star text-dark" style="font-size: 0.7em;"></i> Sugerowany
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $prod->type }}</small>
                                    </div>
                                </div>

                                <div class="text-end d-flex align-items-center gap-3">
                                    
                                    <div>
                                        @if($hasStock)
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                                {{ $totalStock }} szt.
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2">
                                                Brak
                                            </span>
                                        @endif
                                    </div>

                                    <div style="width: 24px;">
                                        @if($isSelected)
                                            <i class="fas fa-check-circle text-primary fs-4"></i>
                                        @else
                                            <i class="far fa-circle text-muted fs-4 opacity-25"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-search fa-2x mb-2 opacity-25"></i>
                                <p>Nie znaleziono produktów.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Dostępna Partia (Rozmiar)</label>
                        <select wire:model="batch_id" class="form-select" @disabled(!$product_id)>
                            <option value="">-- Wybierz rozmiar / partię --</option>
                            @forelse($batches as $batch)
                                <option value="{{ $batch->id }}">
                                    Rozmiar: {{ $batch->size }} | 
                                    Ilość: {{ $batch->current_quantity }} szt. | 
                                    Ważność: {{ $batch->expiration_date->format('Y-m-d') }}
                                </option>
                            @empty
                                @if($product_id)
                                    <option disabled>Brak towaru na stanie!</option>
                                @endif
                            @endforelse
                        </select>
                        @error('batch_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ilość</label>
                        <input type="number" wire:model="quantity" class="form-control" min="1">
                        @error('quantity') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                    
                    {{-- Wyświetlanie błędu walidacji pod spodem --}}
                    @error('product_id') 
                        <div class="card-footer bg-danger bg-opacity-10 border-top-0">
                            <span class="text-danger small"><i class="fas fa-exclamation-circle me-1"></i> {{ $message }}</span> 
                        </div>
                    @enderror
                </div>

                <div class="d-flex gap-2 mb-2">
                    @foreach([6, 12, 24, 36] as $months)
                        <button type="button" 
                                class="btn btn-sm flex-fill {{ $selectedMonths == $months ? 'btn-primary' : 'btn-outline-primary' }}" 
                                wire:click="setPeriod({{ $months }})">
                            
                            +{{ $months }} Miesięcy
                        </button>
                    @endforeach
                </div>

                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-calendar-day"></i></span>
                    
                    <input type="date" 
                        wire:model="due_date" 
                        class="form-control border-start-0 ps-0" 
                        >
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-success btn-lg">
                        <span wire:loading.remove>Zatwierdź Wydanie <i class="fas fa-check"></i></span>
                        <span wire:loading>Przetwarzanie...</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>