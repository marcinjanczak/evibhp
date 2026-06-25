<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="input-group w-50">
            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Szukaj po marce lub rejestracji...">
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
            <i class="fas fa-plus"></i> Dodaj pojazd
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Marka i Model</th>
                        <th>Nr Rejestracyjny</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehicles as $vehicle)
                        <tr class="position-relative">
                            <td class="ps-4 fw-bold">
                                <i class="fas fa-car text-primary me-2"></i> {{ $vehicle->make }}
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border border-secondary p-2 shadow-sm rounded-1" style="font-family: monospace; font-size: 14px;">
                                    {{ $vehicle->license_plate }}
                                </span>
                            </td>
                            <td>
                                @if($vehicle->trips_count > 0)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-road"></i> W trasie</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-parking"></i> Dostępny</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button wire:click="editVehicle({{ $vehicle->id }})" class="btn btn-sm btn-light text-primary" data-bs-toggle="modal" data-bs-target="#editVehicleModal" title="Edytuj">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="deleteVehicle({{ $vehicle->id }})" wire:confirm="Czy na pewno chcesz usunąć ten pojazd?" class="btn btn-sm btn-light text-danger" title="Usuń">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Brak pojazdów we flocie. Dodaj pierwszy pojazd!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Dodaj pojazd -->
    <div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addVehicleModalLabel">Dodaj nowy pojazd</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label">Marka i Model</label>
                        <input type="text" wire:model="make" class="form-control" placeholder="np. Skoda Octavia">
                        @error('make') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Numer Rejestracyjny</label>
                        <input type="text" wire:model="license_plate" class="form-control" placeholder="np. WA 12345" style="text-transform: uppercase;">
                        @error('license_plate') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer px-0 pb-0 mt-3 border-top-0 bg-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button wire:click="addVehicle" type="button" class="btn btn-primary">
                        <i class="fas fa-save"></i> Zapisz pojazd
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Edytuj pojazd -->
    <div class="modal fade" id="editVehicleModal" tabindex="-1" aria-labelledby="editVehicleModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editVehicleModalLabel">Edytuj pojazd</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label">Marka i Model</label>
                        <input type="text" wire:model="edit_make" class="form-control">
                        @error('edit_make') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Numer Rejestracyjny</label>
                        <input type="text" wire:model="edit_license_plate" class="form-control" style="text-transform: uppercase;">
                        @error('edit_license_plate') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer px-0 pb-0 mt-3 border-top-0 bg-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button wire:click="updateVehicle" type="button" class="btn btn-primary">
                        <i class="fas fa-save"></i> Zapisz zmiany
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('close-modal', () => {
            const modalEl = document.getElementById('addVehicleModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            }
        });

        $wire.on('close-modal-edit', () => {
            const modalEl = document.getElementById('editVehicleModal');
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
