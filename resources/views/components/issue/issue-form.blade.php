<div>
    <form wire:submit="save"> <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Pracownik</label>
                <select wire:model="employee_id" class="form-select">
                    <option value="">Wybierz pracownika...</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">
                            {{ $employee->last_name }} {{ $employee->first_name }}
                        </option>
                    @endforeach
                </select>
                @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Produkt</label>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       class="form-control mb-2" placeholder="Wpisz nazwę...">
                
                <select wire:model.live="product_id" class="form-select" size="5">
                    @forelse($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} ({{ $product->size }})
                        </option>
                    @empty
                        <option disabled>Brak produktów o tej nazwie</option>
                    @endforelse
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Dostępna Partia (Dostawa)</label>
                <select wire:model="batch_id" class="form-select" @disabled(!$product_id)>
                    <option value="">-- Najpierw wybierz produkt --</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}">
                            Partia: {{ $batch->batch_number ?? 'Bez nr' }} 
                            (Ważna: {{ $batch->expiration_date->format('Y-m-d') }} | Stan: {{ $batch->current_quantity }})
                        </option>
                    @endforeach
                </select>
                @error('batch_id') <span class="text-danger">{{ $message }}</span> @enderror
                @error('quantity') <span class="text-danger fw-bold">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Ilość do wydania</label>
                <input type="number" wire:model="quantity" class="form-control" min="1">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
            <span wire:loading.remove>Zatwierdź wydanie</span>
            <span wire:loading>
                <i class="fas fa-spinner fa-spin"></i> Przetwarzanie...
            </span>
        </button>
    </form>
</div>