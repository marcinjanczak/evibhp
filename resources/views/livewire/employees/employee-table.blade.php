<div>
<div>
    {{-- GÓRNA BELKA --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        
        {{-- Lewa strona: Wyszukiwarka --}}
        <div class="input-group" style="max-width: 400px;">
            <span class="input-group-text bg-white border-end-0">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   class="form-control border-start-0 ps-0" 
                   placeholder="Szukaj pracownika...">
        </div>

        {{-- Środek: FILTRY (Nowe przyciski) --}}
        <div class="btn-group shadow-sm" role="group">
            
            {{-- Przycisk: Wszyscy --}}
            <button type="button" wire:click="$set('filter', 'all')" 
                    class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                Wszyscy
            </button>

            {{-- Przycisk: Mają sprzęt --}}
            <button type="button" wire:click="$set('filter', 'has_items')" 
                    class="btn btn-sm {{ $filter === 'has_items' ? 'btn-primary' : 'btn-outline-secondary' }}"
                    title="Pokaż tylko osoby posiadające sprzęt">
                <i class="fas fa-boxes me-1"></i> Mają sprzęt
            </button>

            {{-- Przycisk: Zaległości --}}
            <button type="button" wire:click="$set('filter', 'overdue')" 
                    class="btn btn-sm {{ $filter === 'overdue' ? 'btn-danger' : 'btn-outline-danger' }}"
                    title="Pokaż tylko osoby z przeterminowanym zwrotem">
                <i class="fas fa-exclamation-circle me-1"></i> Zaległości
            </button>
        </div>
        
        {{-- Prawa strona: Dodaj pracownika --}}
        <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
            <i class="fas fa-user-plus"></i> <span class="d-none d-lg-inline">Dodaj</span>
        </button>
    </div>

    {{-- TABELA (Reszta bez zmian, tylko upewnij się, że masz wyświetlanie licznika z poprzedniej odpowiedzi) --}}
    <div class="card shadow-sm border-0 overflow-hidden">
        {{-- ... tabela ... --}}
    </div>
</div>

    <div class="table-responsive fs-6">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary">
                <tr>
                    <th class="ps-4 py-3">Pracownik</th>
                    <th>Stanowisko</th>
                    <th>Wypożyczenia</th>
                    <th class="text-end pe-4">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr onclick="window.location='{{ route('employees.show', $employee->id) }}'" style="cursor: pointer;" class="position-relative">
                        {{-- PRACOWNIK --}}
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $employee->last_name }} {{ $employee->first_name }}</div>
                            {{-- Opcjonalnie: alerty pod nazwiskiem --}}
                            @if($employee->has_overdue_issues)
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger p-1 mt-1" style="font-size: 0.7em;">
                                    <i class="fas fa-exclamation-circle"></i> Ma zaległości!
                                </span>
                            @elseif($employee->has_due_soon_issues)
                                <span class="badge bg-warning bg-opacity-10 text-dark border border-warning p-1 mt-1" style="font-size: 0.7em;">
                                    <i class="fas fa-clock"></i> Termin blisko
                                </span>
                            @endif
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

                        <td>
                            @if($employee->active_issues_count > 0)
                                <span class="badge bg-primary rounded-pill">
                                    {{ $employee->active_issues_count }} szt.
                                </span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>

                        {{-- AKCJE --}}
                        <td class="text-end pe-4" onclick="event.stopPropagation();">
                            <div class="btn-group">
                                <button wire:click="$dispatch('edit-employee', { id: {{ $employee->id }} })" 
                                        class="btn btn-sm btn-light text-primary" 
                                        data-bs-toggle="modal" data-bs-target="#addEmployeeModal"
                                        title="Edytuj pracownika">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:confirm="Czy na pewno usunąć tego pracownika?" 
                                        wire:click="delete({{ $employee->id }})" 
                                        class="btn btn-sm btn-light text-danger"
                                        title="Usuń pracownika">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    {{-- ... Twój kod pustej tabeli ... --}}
                @endforelse
            </tbody>
        </table>
    </div>
        
        <div class="card-footer bg-white border-0 py-3">
            {{ $employees->links() }}
        </div>
    </div>
</div>