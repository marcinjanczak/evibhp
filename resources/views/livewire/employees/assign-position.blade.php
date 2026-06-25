<div>
    <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
        <p class="text-muted mb-0">{{ $employee->position->name ?? 'Brak stanowiska' }}</p>
        
        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignPositionModal">
            <i class="fas fa-edit"></i>
        </button>

        @if($employee->position_id)
            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removePosition" wire:confirm="Czy na pewno chcesz usunąć to stanowisko?">
                <i class="fas fa-times"></i>
            </button>
        @endif
    </div>

    <!-- Modal przypisywania stanowiska -->
    <div class="modal fade" id="assignPositionModal" tabindex="-1" aria-labelledby="assignPositionModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog text-start">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="assignPositionModalLabel">Przypisz stanowisko</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label">Wyszukaj stanowisko</label>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control mb-2" placeholder="Wpisz nazwę stanowiska...">
                        
                        <div class="list-group shadow-sm">
                            @forelse($positions as $pos)
                                <button type="button" wire:click="selectPosition({{ $pos->id }})" 
                                        class="list-group-item list-group-item-action {{ $selectedPositionId == $pos->id ? 'active' : '' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-briefcase {{ $selectedPositionId == $pos->id ? 'text-white' : 'text-secondary' }}"></i>
                                        <span class="fw-medium">{{ $pos->name }}</span>
                                    </div>
                                </button>
                            @empty
                                <div class="list-group-item text-muted text-center py-3">Brak wyników...</div>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-0 pb-0 mt-3 border-top-0 bg-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button wire:click="assignPosition" type="button" class="btn btn-success">
                        <i class="fas fa-check"></i> Przypisz
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('close-modal', () => {
            const modalEl = document.getElementById('assignPositionModal');
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
