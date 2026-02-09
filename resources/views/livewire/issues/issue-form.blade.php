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

                <div class="mb-3">
                    <label class="form-label">Produkt</label>
                    <select wire:model.live="product_id" class="form-select">
                        <option value="">-- Wybierz produkt --</option>
                        
                        @if(!empty($suggestedProductIds))
                            <optgroup label="⭐ SUGEROWANE DLA STANOWISKA">
                                @foreach($products->whereIn('id', $suggestedProductIds) as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }} ({{ $prod->type }})</option>
                                @endforeach
                            </optgroup>
                        @endif

                        <optgroup label="Pozostałe produkty">
                            @foreach($products->whereNotIn('id', $suggestedProductIds) as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }} ({{ $prod->type }})</option>
                            @endforeach
                        </optgroup>
                    </select>
                    @error('product_id') <span class="text-danger small">{{ $message }}</span> @enderror
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