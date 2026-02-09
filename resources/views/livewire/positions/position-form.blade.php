<div>
    <form wire:submit="save">
        <div class="mb-3">
            <label class="form-label">Nazwa stanowiska <span class="text-danger">*</span></label>
            <input type="text" wire:model="name" class="form-control" placeholder="np. Spawacz TIG">
            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Sugerowane wyposażenie (Wybierz produkty)</label>
            <div class="card p-2" style="max-height: 200px; overflow-y: auto;">
                @foreach($allProducts as $product)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               value="{{ $product->id }}" 
                               wire:model="selectedProducts" 
                               id="prod_{{ $product->id }}">
                        <label class="form-check-label" for="prod_{{ $product->id }}">
                            {{ $product->name }} <small class="text-muted">({{ $product->type }})</small>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="form-text text-muted">Zaznacz, co powinien otrzymać pracownik na tym stanowisku.</div>
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