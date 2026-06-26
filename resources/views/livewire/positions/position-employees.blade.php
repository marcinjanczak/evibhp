<div>
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-users text-secondary me-2"></i> Przypisani Pracownicy</h5>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="fas fa-plus"></i> Dodaj pracownika
            </button>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Pracownik</th>
                        <th class="text-end pe-4">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($position->employees as $employee)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold">{{ $employee->last_name }} {{ $employee->first_name }}</span>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <button wire:confirm="Czy na pewno chcesz usunąć tego pracownika ze stanowiska?" wire:click="removeEmployee({{ $employee->id }})" class="btn btn-sm btn-light text-danger" title="Usuń ze stanowiska">
                                    <i class="fas fa-xmark"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center py-4 text-muted">
                                Brak pracowników przypisanych do tego stanowiska.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal dodawania pracownika -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Dodaj pracownika do stanowiska</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label">Wyszukaj pracownika</label>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control mb-2" placeholder="Wpisz imię lub nazwisko...">
                        
                        <div class="list-group shadow-sm">
                            @forelse($availableEmployees as $emp)
                                <button type="button" wire:click="selectEmployee({{ $emp->id }})" 
                                        class="list-group-item list-group-item-action {{ $selectedEmployee == $emp->id ? 'active' : '' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-user-circle {{ $selectedEmployee == $emp->id ? 'text-white' : 'text-secondary' }}"></i>
                                        <span class="fw-medium">{{ $emp->last_name }} {{ $emp->first_name }}</span>
                                    </div>
                                </button>
                            @empty
                                <div class="list-group-item text-muted text-center py-3">Brak wyników...</div>
                            @endforelse
                        </div>
                        @error('selectedEmployee') <span class="text-danger small mt-2 d-block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer px-0 pb-0 mt-3 border-top-0 bg-white">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button wire:click="addEmployee" type="button" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Dodaj
                    </button>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('close-modal', () => {
            const modalEl = document.getElementById('addEmployeeModal');
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
