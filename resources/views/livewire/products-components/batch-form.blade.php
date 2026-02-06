<div class="card border-success shadow-sm">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Przyjmij nową dostawę (Partię)</h5>
    </div>
    <div class="card-body bg-light">
        <form wire:submit="save">
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Rozmiar <span class="text-danger">*</span></label>
                    <input type="text" wire:model="size" class="form-control" placeholder="np. 42 / L">
                    @error('size') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Ilość sztuk <span class="text-danger">*</span></label>
                    <input type="number" wire:model="quantity" class="form-control" min="1">
                    @error('quantity') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label">Data Ważności</label>
                    <input type="date" wire:model="expiration_date" class="form-control">
                    @error('expiration_date') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-3 mb-3">
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

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    <span wire:loading.remove><i class="fas fa-save"></i> Dodaj do magazynu</span>
                    <span wire:loading>Zapisywanie...</span>
                </button>
            </div>
        </form>
    </div>
</div>