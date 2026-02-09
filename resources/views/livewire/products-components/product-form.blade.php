<div>
    <form wire:submit="save">
        
        {{-- Nagłówek dynamiczny --}}
        <div class="alert {{ $product ? 'alert-warning' : 'alert-info' }} border-0 mb-4">
            @if($product)
                <i class="fas fa-edit me-2"></i> Edytujesz produkt: <strong>{{ $product->name }}</strong>
            @else
                <i class="fas fa-plus-circle me-2"></i> Tworzysz nowy produkt
            @endif
        </div>

        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Nazwa przedmiotu <span class="text-danger">*</span></label>
                <input type="text" wire:model="name" class="form-control">
                @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Typ / Kategoria <span class="text-danger">*</span></label>
                <input type="text" wire:model="type" class="form-control">
                @error('type') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            {{-- ZDJĘCIE --}}
            <div class="col-md-12 mb-3">
                <label class="form-label">Zdjęcie poglądowe</label>
                <input type="file" wire:model="preview_image" class="form-control" accept="image/*">
                @error('preview_image') <span class="text-danger small">{{ $message }}</span> @enderror

                <div class="mt-3">
                    {{-- Przypadek 1: Wgrano nowe zdjęcie (tymczasowe) --}}
                    @if ($preview_image)
                        <p class="small text-success mb-1">Nowe zdjęcie:</p>
                        <img src="{{ $preview_image->temporaryUrl() }}" class="img-thumbnail" style="height: 100px;">
                    
                    {{-- Przypadek 2: Edycja - brak nowego, ale jest stare w bazie --}}
                    @elseif ($product && $product->preview_image_path)
                        <p class="small text-muted mb-1">Obecne zdjęcie:</p>
                        <img src="{{ Storage::url($product->preview_image_path) }}" class="img-thumbnail" style="height: 100px; opacity: 0.7">
                    @endif
                </div>
            </div>
        </div>
        
        @error('base') <div class="alert alert-danger">{{ $message }}</div> @enderror

        <div class="modal-footer px-0 pb-0 border-top-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
            
            <button type="submit" class="btn {{ $product ? 'btn-warning' : 'btn-primary' }}">
                <span wire:loading.remove>
                    @if($product)
                        <i class="fas fa-save"></i> Zapisz zmiany
                    @else
                        <i class="fas fa-plus"></i> Utwórz produkt
                    @endif
                </span>
                <span wire:loading>Przetwarzanie...</span>
            </button>
        </div>
    </form>
</div>