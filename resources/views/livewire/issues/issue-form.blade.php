<div>
    <form wire:submit="save">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-hand-holding-box me-2"></i> Wydawanie Towaru</h5>
            </div>
            <div class="card-body">
                
                {{-- 1. WYBÓR PRACOWNIKA --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Kto pobiera towar?</label>
                    <select wire:model.live="employee_id" class="form-select form-select-lg">
                        <option value="">-- Wybierz pracownika --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">
                                {{ $emp->last_name }} {{ $emp->first_name }} 
                                ({{ $emp->position->name ?? 'Brak stanowiska' }})
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                {{-- 2. WYBÓR PRODUKTU (Z podziałem na sugerowane) --}}
                <div class="mb-3">
                    <label class="form-label">Produkt</label>
                    <select wire:model.live="product_id" class="form-select" @disabled(!$employee_id)>
                        <option value="">-- Wybierz produkt --</option>
                        
                        {{-- GRUPA 1: SUGEROWANE --}}
                        @if(!empty($suggestedProductIds))
                            <optgroup label="⭐ SUGEROWANE DLA STANOWISKA">
                                @foreach($products->whereIn('id', $suggestedProductIds) as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }} ({{ $prod->type }})</option>
                                @endforeach
                            </optgroup>
                        @endif

                        {{-- GRUPA 2: RESZTA --}}
                        <optgroup label="Pozostałe produkty">
                            @foreach($products->whereNotIn('id', $suggestedProductIds) as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }} ({{ $prod->type }})</option>
                            @endforeach
                        </optgroup>
                    </select>
                    @error('product_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="row">
                    {{-- 3. WYBÓR PARTII (ROZMIARU) --}}
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

                    {{-- 4. ILOŚĆ --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ilość</label>
                        <input type="number" wire:model="quantity" class="form-control" min="1">
                        @error('quantity') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
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