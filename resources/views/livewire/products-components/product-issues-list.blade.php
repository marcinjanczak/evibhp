<div>
    <div class="row mb-3 g-2 align-items-center">
        <div class="col-md-6">
            <input type="text" wire:model.live.debounce.300ms="searchEmployee" class="form-control" placeholder="Szukaj po nazwisku/imieniu pracownika...">
        </div>
        <div class="col-md-6">
            <select wire:model.live="statusFilter" class="form-select">
                <option value="">Wszystkie statusy</option>
                <option value="active">W użyciu</option>
                <option value="returned">Zwrócone</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white shadow-sm rounded">
            <thead class="bg-light text-secondary">
                <tr>
                    <th>Data Wydania</th>
                    <th>Pracownik</th>
                    <th>Ilość</th>
                    <th>Rozmiar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($issues as $issue)
                    <tr>
                        <td><div class="fw-bold">{{ $issue->issued_at->format('Y-m-d') }}</div></td>
                        <td>
                            <a href="{{ route('employees.show', $issue->employee->id) }}" class="text-decoration-none">
                                {{ $issue->employee->first_name }} {{ $issue->employee->last_name }}
                            </a>
                        </td>
                        <td>{{ $issue->quantity }} szt.</td>
                        <td><span class="badge bg-secondary">{{ $issue->batch->size ?? '-' }}</span></td>
                        <td>
                            @if($issue->returned_at)
                                <span class="badge bg-success"><i class="fas fa-check"></i> Zwrócono ({{ $issue->returned_at->format('Y-m-d') }})</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> W użyciu</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-clipboard-list fa-2x mb-2 opacity-50"></i><br>
                            Brak wydań spełniających kryteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $issues->links() }}
    </div>
</div>
