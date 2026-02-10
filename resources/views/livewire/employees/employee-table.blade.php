<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="input-group w-50">
            <span class="input-group-text bg-white border-end-0">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   class="form-control border-start-0 ps-0" 
                   placeholder="Szukaj pracownika (imię, nazwisko, email)...">
        </div>
        
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="fas fa-user-plus"></i> Dodaj pracownika
        </button>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3">Pracownik</th>
                        <th>Stanowisko</th>
                        <th class="text-end pe-4">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $employee->last_name }} {{ $employee->first_name }}</div>
                                </div>
                            </td>

                            <td>
                                @if($employee->position)
                                    <span class="badge text-dark bg-opacity-25 px-3 py-2 rounded-pill">
                                        {{ $employee->position->name }}
                                    </span>
                                @else
                                    <span class="text-muted small fst-italic">Brak stanowiska</span>
                                @endif
                            </td>

                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-light">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-users-slash fa-2x mb-3 text-secondary opacity-50"></i><br>
                                    Nie znaleziono pracowników pasujących do "<strong>{{ $search }}</strong>".
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white border-0 py-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>