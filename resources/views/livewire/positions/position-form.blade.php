<div>

    <form wire:submit="save">
        <div class="mb-3">
            <label class="form-label">Nazwa stanowiska <span class="text-danger">*</span></label>
            <input type="text" wire:model="name" class="form-control" placeholder="np. Spawacz TIG">
            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror

        </div>

       <div class="mb-3">
    <label class="form-label fw-bold">Sugerowane wyposażenie</label>
    
    {{-- Wyszukiwarka --}}
    <input type="text" 
           class="form-control mb-2" 
           placeholder="Szukaj produktu..." 
           wire:model.live.debounce.300ms="searchProduct">

    {{-- Lista produktów --}}
    <div class="d-flex flex-column gap-2 border rounded bg-light p-2" style="max-height: 400px; overflow-y: auto;">
        
            @forelse($products as $product)
                @php
                    // Sprawdzamy, czy ID jest w tablicy wybranych (to jest multiselect!)
                    $isSelected = in_array($product->id, $selectedProducts);
                @endphp

                <div wire:click="toggleProduct({{ $product->id }})"
                    class="card border-0 shadow-sm p-3 transition-hover
                    {{ $isSelected ? 'ring-2 ring-primary bg-primary bg-opacity-10' : 'bg-white' }}"
                    style="cursor: pointer; transition: all 0.2s ease;">
                    
                    <div class="d-flex align-items-center justify-content-between">
                        
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0
                                {{ $isSelected ? 'bg-primary text-white' : 'bg-secondary bg-opacity-10 text-secondary' }}"
                                style="width: 40px; height: 40px; font-weight: bold;">
                                {{ substr($product->name, 0, 1) }}
                            </div>

                            <div>
                                <h6 class="mb-0 fw-bold text-dark">{{ $product->name }}</h6>
                                <small class="text-muted">{{ $product->type }}</small>
                            </div>
                        </div>

                        <div style="font-size: 1.5rem;">
                            @if($isSelected)
                                <i class="fas fa-check-square text-primary"></i>
                            @else
                                <i class="far fa-square text-muted opacity-25"></i>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    Brak produktów pasujących do wyszukiwania.
                </div>
            @endforelse
        </div>
        
        <div class="form-text mt-2">
            Wybrano: <span class="fw-bold">{{ count($selectedProducts) }}</span> pozycji.
        </div>
    </div>

        <div class="modal-footer px-0 pb-0 border-top-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
            <button type="submit" class="btn btn-primary">
                <span wire:loading.remove>
                    {{ $position ? 'Zaktualizuj' : 'Zapisz stanowisko' }}
                </span>
                <span wire:loading>Przetwarzanie...</span>
            </button>
        </div>
    </form>
</div>