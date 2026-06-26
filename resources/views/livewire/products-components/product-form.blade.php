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
                
                @if(!$isNewType && count($existingTypes) > 0)
                    <select wire:model.live="type" class="form-select">
                        <option value="">-- Wybierz kategorię --</option>
                        @foreach($existingTypes as $existingType)
                            @if(!empty($existingType))
                                <option value="{{ $existingType }}">{{ $existingType }}</option>
                            @endif
                        @endforeach
                        <option value="NEW" class="text-success fw-bold">➕ Dodaj nową kategorię...</option>
                    </select>
                @endif

                @if($isNewType || count($existingTypes) == 0)
                    <div class="input-group">
                        <input type="text" wire:model="type" class="form-control" placeholder="Wpisz nazwę nowej kategorii...">
                        @if(count($existingTypes) > 0)
                            <button class="btn btn-outline-secondary" type="button" wire:click="$set('isNewType', false); $set('type', '')" title="Wróć do listy">
                                <i class="fas fa-undo"></i>
                            </button>
                        @endif
                    </div>
                @endif
                
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
                        <div class="d-flex align-items-center">
                            <img src="{{ $preview_image->temporaryUrl() }}" class="img-thumbnail me-3" style="height: 100px;">
                            <button type="button" wire:click="$set('preview_image', null)" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times"></i> Anuluj nową grafikę
                            </button>
                        </div>
                    
                    {{-- Przypadek 2: Edycja - brak nowego, ale jest stare w bazie --}}
                    @elseif ($product && $product->preview_image_path)
                        <p class="small text-muted mb-1">Obecne zdjęcie:</p>
                        <div class="d-flex align-items-center">
                            <img src="{{ Storage::url($product->preview_image_path) }}" class="img-thumbnail me-3" style="height: 100px;">
                            <button type="button" wire:click="removeImage" wire:confirm="Czy na pewno chcesz usunąć to zdjęcie?" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash"></i> Usuń zdjęcie z bazy
                            </button>
                        </div>
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