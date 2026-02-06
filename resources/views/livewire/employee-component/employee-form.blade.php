<div>
    <form wire:submit="save">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Imię <span class="text-danger">*</span></label>
                <input type="text" wire:model="first_name" class="form-control" placeholder="np. Jan">
                @error('first_name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Nazwisko <span class="text-danger">*</span></label>
                <input type="text" wire:model="last_name" class="form-control" placeholder="np. Kowalski">
                @error('last_name') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Adres Email <span class="text-danger">*</span></label>
                <input type="email" wire:model="email" class="form-control" placeholder="jan.kowalski@firma.pl">
                @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Stanowisko</label>
                <select wire:model="position_id" class="form-select">
                    <option value="">-- Brak stanowiska --</option>
                    @foreach($positions as $pos)
                        <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                    @endforeach
                </select>
                @error('position_id') <span class="text-danger small">{{ $message }}</span> @enderror
            </div>
        </div>

        @error('base')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="modal-footer px-0 pb-0 border-top-0 mt-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
            <button type="submit" class="btn btn-primary">
                <span wire:loading.remove><i class="fas fa-save"></i> Zapisz pracownika</span>
                <span wire:loading>Zapisywanie...</span>
            </button>
        </div>
    </form>
</div>