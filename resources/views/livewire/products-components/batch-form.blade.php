<div> <form wire:submit="save">
        
        <div class="alert alert-light border mb-3">
            <small class="text-muted">Dodajesz partię do produktu: <strong>{{ $product->name }}</strong></small>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Rozmiar <span class="text-danger">*</span></label>
                <input type="text" wire:model="size" class="form-control" placeholder="">
                @error('size') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ilość sztuk <span class="text-danger">*</span></label>
                <input type="number" wire:model="quantity" class="form-control" min="1">
                @error('quantity') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Data Ważności</label>
                <input type="date" wire:model="expiration_date" class="form-control mb-2">
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="setExpirationMonths(6)">+6 m-cy</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="setExpirationMonths(12)">+12 m-cy</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="setExpirationMonths(24)">+24 m-ce</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="setExpirationMonths(36)">+36 m-cy</button>
                </div>
                @error('expiration_date') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nr Partii / Faktury</label>
                <input type="text" wire:model="batch_number" class="form-control" placeholder="Opcjonalne">
                @error('batch_number') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Skan faktury (PDF)</label>
                <input type="file" wire:model="invoice_pdf" class="form-control" accept="application/pdf">
                @error('invoice_pdf') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>

        @error('base')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="modal-footer px-0 pb-0 mt-3 border-top-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
            
            <button type="submit" class="btn btn-success">
                <span wire:loading.remove><i class="fas fa-save"></i> Zapisz dostawę</span>
                <span wire:loading>Przetwarzanie...</span>
            </button>
        </div>
    </form>
</div>