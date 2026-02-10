<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="input-group w-50">
            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
            <input type="text" wire:model.live="search" class="form-control" placeholder="Szukaj stanowiska...">
        </div>
        

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#positionModal">
            <i class="fas fa-plus"></i> Dodaj stanowisko
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Nazwa Stanowiska</th>
                    <th>Pracowników</th>
                    <th>Sugerowane Produkty</th>
                    <th class="text-end pe-4">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $position)
                    <tr>
                        <td class="ps-4 fw-bold">{{ $position->name }}</td>
                        <td><span class="badge bg-secondary">{{ $position->employees_count }}</span></td>
                        <td>
                            <span class="badge bg-success">{{ $position->products_count }}</span>
                            <small class="text-muted ms-1">przypisanych</small>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                {{-- NOWY PRZYCISK PODGLĄDU --}}
                                <a href="{{ route('positions.show', $position->id) }}" class="btn btn-sm btn-light text-secondary" title="Szczegóły">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <button wire:click="$dispatch('edit-position', { id: {{ $position->id }} })" 
                                        class="btn btn-sm btn-light text-primary" 
                                        data-bs-toggle="modal" data-bs-target="#positionModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4">Brak stanowisk.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $positions->links() }}</div>
    </div>
</div>