<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-secondary">Ewidencja wyjazdów</h5>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#startTripModal">
            <i class="fas fa-play"></i> Zgłoś wyjazd
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Pojazd</th>
                        <th>Kierowca</th>
                        <th>Trasa (Skąd - Dokąd)</th>
                        <th>Czas wyjazdu / powrotu</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trips as $trip)
                        <tr class="position-relative">
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $trip->vehicle->make ?? 'Usunięto' }}</div>
                                <div class="small text-muted">{{ $trip->vehicle->license_plate ?? '' }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $trip->employee->first_name ?? '' }} {{ $trip->employee->last_name ?? 'Usunięto' }}</div>
                            </td>
                            <td>
                                <div><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $trip->departure_location }}</div>
                                <div><i class="fas fa-flag-checkered text-success me-1"></i> {{ $trip->destination }}</div>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="fas fa-sign-out-alt text-primary"></i> 
                                    {{ $trip->departure_date->format('Y-m-d') }} {{ \Carbon\Carbon::parse($trip->departure_time)->format('H:i') }}
                                </div>
                                <div class="small text-muted">
                                    @if($trip->status == 'completed')
                                        <i class="fas fa-sign-in-alt text-success"></i>
                                        {{ $trip->return_date->format('Y-m-d') }} {{ \Carbon\Carbon::parse($trip->return_time)->format('H:i') }}
                                    @else
                                        <i class="fas fa-clock text-warning"></i> 
                                        Planowany powrót: {{ \Carbon\Carbon::parse($trip->estimated_return_time)->format('H:i') }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($trip->status == 'in_progress')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-spinner fa-spin"></i> W trakcie</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Zakończony</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    @if($trip->status == 'in_progress')
                                        <button wire:click="openEndTrip({{ $trip->id }})" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#endTripModal" title="Zakończ wyjazd">
                                            <i class="fas fa-flag-checkered"></i> Zakończ
                                        </button>
                                    @endif
                                    <button wire:click="deleteTrip({{ $trip->id }})" wire:confirm="Czy na pewno usunąć ten wyjazd?" class="btn btn-sm btn-outline-danger" title="Usuń z historii">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Brak zarejestrowanych wyjazdów.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Rozpocznij Wyjazd -->
    <div class="modal fade" id="startTripModal" tabindex="-1" aria-labelledby="startTripModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="startTripModalLabel">Zgłoś nowy wyjazd</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Pojazd</label>
                            <select wire:model="vehicle_id" class="form-select">
                                <option value="">-- Wybierz pojazd --</option>
                                @foreach($vehicles as $veh)
                                    <option value="{{ $veh->id }}">{{ $veh->make }} ({{ $veh->license_plate }})</option>
                                @endforeach
                            </select>
                            @error('vehicle_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 position-relative">
                            <label class="form-label">Kierowca</label>
                            
                            @if($employee_id)
                                <div class="input-group">
                                    <input type="text" class="form-control bg-light" value="{{ $selectedEmployeeName }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" wire:click="$set('employee_id', null)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @else
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                    <input type="text" wire:model.live.debounce.300ms="searchEmployee" class="form-control" placeholder="Wpisz nazwisko...">
                                </div>

                                @if(strlen($searchEmployee) > 0)
                                    <div class="position-absolute w-100 z-3 mt-1 shadow-sm rounded-bottom bg-white border" style="max-height: 250px; overflow-y: auto;">
                                        
                                        @if($driversList->count() > 0)
                                            <div class="px-3 py-2 bg-light border-bottom text-muted small fw-bold">
                                                KIEROWCY
                                            </div>
                                            <div class="list-group list-group-flush">
                                                @foreach($driversList as $driver)
                                                    <button type="button" wire:click="selectEmployee({{ $driver->id }}, '{{ addslashes($driver->last_name . ' ' . $driver->first_name) }}')" class="list-group-item list-group-item-action py-2">
                                                        <i class="fas fa-id-card text-success me-2"></i>
                                                        <span class="fw-bold">{{ $driver->last_name }} {{ $driver->first_name }}</span>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($othersList->count() > 0)
                                            <div class="px-3 py-2 bg-light border-bottom border-top text-muted small fw-bold">
                                                POZOSTALI PRACOWNICY
                                            </div>
                                            <div class="list-group list-group-flush">
                                                @foreach($othersList as $other)
                                                    <button type="button" wire:click="selectEmployee({{ $other->id }}, '{{ addslashes($other->last_name . ' ' . $other->first_name) }}')" class="list-group-item list-group-item-action py-2">
                                                        <i class="fas fa-user text-muted me-2"></i>
                                                        <span>{{ $other->last_name }} {{ $other->first_name }}</span>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($driversList->isEmpty() && $othersList->isEmpty())
                                            <div class="p-3 text-center text-muted small">
                                                Brak wyników.
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endif

                            @error('employee_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Data wyjazdu</label>
                            <input type="date" wire:model="departure_date" class="form-control">
                            @error('departure_date') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Godzina wyjazdu</label>
                            <div class="d-flex gap-2">
                                <select wire:model="departure_time_h" class="form-select">
                                    <option value="">Godz.</option>
                                    @for($h=0; $h<24; $h++)
                                        <option value="{{ $h }}">{{ sprintf('%02d', $h) }}</option>
                                    @endfor
                                </select>
                                <span class="align-self-center">:</span>
                                <select wire:model="departure_time_m" class="form-select">
                                    <option value="">Min.</option>
                                    @for($m=0; $m<60; $m+=5)
                                        <option value="{{ $m }}">{{ sprintf('%02d', $m) }}</option>
                                    @endfor
                                </select>
                            </div>
                            @error('departure_time_h') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            @error('departure_time_m') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Przewidywany powrót</label>
                            <div class="d-flex gap-2">
                                <select wire:model="estimated_return_time_h" class="form-select">
                                    <option value="">Godz.</option>
                                    @for($h=0; $h<24; $h++)
                                        <option value="{{ $h }}">{{ sprintf('%02d', $h) }}</option>
                                    @endfor
                                </select>
                                <span class="align-self-center">:</span>
                                <select wire:model="estimated_return_time_m" class="form-select">
                                    <option value="">Min.</option>
                                    @for($m=0; $m<60; $m+=5)
                                        <option value="{{ $m }}">{{ sprintf('%02d', $m) }}</option>
                                    @endfor
                                </select>
                            </div>
                            @error('estimated_return_time_h') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            @error('estimated_return_time_m') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 position-relative">
                            <label class="form-label">Skąd (miejsce wyjazdu)</label>
                            <input type="text" wire:model.live.debounce.300ms="departure_location" class="form-control" placeholder="np. Siedziba firmy" autocomplete="off">
                            @if(strlen($departure_location) > 0 && $pastLocations->count() > 0 && !$pastLocations->contains($departure_location))
                                <div class="position-absolute w-100 z-3 mt-1 shadow-sm rounded-bottom bg-white border" style="max-height: 200px; overflow-y: auto;">
                                    <div class="list-group list-group-flush">
                                        @foreach($pastLocations as $loc)
                                            <button type="button" wire:click="selectDeparture('{{ addslashes($loc) }}')" class="list-group-item list-group-item-action py-2">
                                                <i class="fas fa-map-marker-alt text-muted me-2"></i> {{ $loc }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @error('departure_location') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-md-6 position-relative">
                            <label class="form-label">Cel (dokąd)</label>
                            <input type="text" wire:model.live.debounce.300ms="destination" class="form-control" placeholder="np. Budowa Warszawa" autocomplete="off">
                            @if(strlen($destination) > 0 && $pastDestinations->count() > 0 && !$pastDestinations->contains($destination))
                                <div class="position-absolute w-100 z-3 mt-1 shadow-sm rounded-bottom bg-white border" style="max-height: 200px; overflow-y: auto;">
                                    <div class="list-group list-group-flush">
                                        @foreach($pastDestinations as $dest)
                                            <button type="button" wire:click="selectDestination('{{ addslashes($dest) }}')" class="list-group-item list-group-item-action py-2">
                                                <i class="fas fa-flag-checkered text-muted me-2"></i> {{ $dest }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @error('destination') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-0 pb-0 mt-3 border-top-0 bg-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button wire:click="startTrip" type="button" class="btn btn-primary">
                        <i class="fas fa-play"></i> Rozpocznij wyjazd
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Zakończ Wyjazd -->
    <div class="modal fade" id="endTripModal" tabindex="-1" aria-labelledby="endTripModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="endTripModalLabel">Zakończ wyjazd</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Data powrotu</label>
                            <input type="date" wire:model="return_date" class="form-control">
                            @error('return_date') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Godzina powrotu</label>
                            <div class="d-flex gap-2">
                                <select wire:model="return_time_h" class="form-select">
                                    <option value="">Godz.</option>
                                    @for($h=0; $h<24; $h++)
                                        <option value="{{ $h }}">{{ sprintf('%02d', $h) }}</option>
                                    @endfor
                                </select>
                                <span class="align-self-center">:</span>
                                <select wire:model="return_time_m" class="form-select">
                                    <option value="">Min.</option>
                                    @for($m=0; $m<60; $m+=5)
                                        <option value="{{ $m }}">{{ sprintf('%02d', $m) }}</option>
                                    @endfor
                                </select>
                            </div>
                            @error('return_time_h') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            @error('return_time_m') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-0 pb-0 mt-3 border-top-0 bg-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button wire:click="endTrip" type="button" class="btn btn-success">
                        <i class="fas fa-check"></i> Zapisz powrót
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('close-modal-start', () => {
            const modalEl = document.getElementById('startTripModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) {
                    modal.hide();
                }
            }
        });
        
        $wire.on('close-modal-end', () => {
            const modalEl = document.getElementById('endTripModal');
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
