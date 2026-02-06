<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h3 class="mb-0">Definiowanie nowego produktu</h3>
    </div>
    
    <div class="card-body">
        <form wire:submit="save">
            
            <div class="alert alert-info border-0 bg-light">
                <i class="fas fa-info-circle text-primary"></i> 
                Tutaj tworzysz tylko kartotekę produktu. Ilość i daty ważności dodasz w kolejnym kroku (Dostawy).
            </div>

            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nazwa przedmiotu <span class="text-danger">*</span></label>
                    <input type="text" wire:model="name" class="form-control" placeholder="np. Kask 3M Peltor">
                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Typ / Kategoria <span class="text-danger">*</span></label>
                    <input type="text" wire:model="type" class="form-control" placeholder="np. ŚOI Głowa">
                    @error('type') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Zdjęcie poglądowe</label>
                    <input type="file" wire:model="preview_image" class="form-control" accept="image/*">
                    @error('preview_image') <span class="text-danger small">{{ $message }}</span> @enderror

                    @if ($preview_image)
                        <div class="mt-3 p-2 border rounded d-inline-block bg-light">
                            <img src="{{ $preview_image->temporaryUrl() }}" class="img-fluid" style="max-height: 200px;">
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="{{ route('items.index') }}" class="btn btn-outline-secondary" wire:navigate>Anuluj</a>
                <button type="submit" class="btn btn-primary px-4">
                    <span wire:loading.remove>Utwórz produkt i przejdź do dostaw <i class="fas fa-arrow-right"></i></span>
                    <span wire:loading>Tworzenie...</span>
                </button>
            </div>
        </form>
    </div>
</div>