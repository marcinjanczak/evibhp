<div wire:ignore.self class="modal fade" id="issueBatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-hand-holding-box me-2"></i> Wydanie z wybranej partii
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($batch)
                    <div class="alert alert-info">
                        <strong>Przedmiot:</strong> {{ $batch->product->name }}<br>
                        <strong>Rozmiar:</strong> {{ $batch->size }}<br>
                        <strong>Dostępna ilość w tej partii:</strong> {{ $batch->current_quantity }}
                    </div>

                    <form wire:submit.prevent="issueItem">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Szukaj Pracownika <span class="text-danger">*</span></label>
                            <input type="text" wire:model.live.debounce.300ms="searchEmployee" class="form-control" placeholder="Wpisz imię lub nazwisko...">
                            @if(!empty($searchEmployee) && empty($employee_id))
                                <div class="list-group mt-2 position-absolute w-100 shadow" style="z-index: 1000; max-width: 95%;">
                                    @forelse($employees as $emp)
                                        <button type="button" class="list-group-item list-group-item-action" 
                                                wire:click="selectEmployee({{ $emp->id }}, '{{ $emp->first_name }} {{ $emp->last_name }}')">
                                            {{ $emp->first_name }} {{ $emp->last_name }} 
                                            <small class="text-muted">({{ $emp->position->name ?? 'Brak stanowiska' }})</small>
                                        </button>
                                    @empty
                                        <div class="list-group-item text-muted">Brak wyników</div>
                                    @endforelse
                                </div>
                            @endif
                            @error('employee_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ilość do wydania <span class="text-danger">*</span></label>
                            <input type="number" wire:model="quantity" class="form-control" min="1" max="{{ $batch->current_quantity }}" required>
                            @error('quantity') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                            <button type="submit" class="btn btn-primary" @if(empty($employee_id)) disabled @endif>
                                <i class="fas fa-check"></i> Wydaj
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-4 text-muted">
                        Ładowanie danych partii...
                    </div>
                @endif
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('close-modal-issue', () => {
            const modalEl = document.getElementById('issueBatchModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            }
        });
    </script>
    @endscript
</div>
