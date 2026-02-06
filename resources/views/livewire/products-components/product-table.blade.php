<div>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       class="form-control" 
                       placeholder="Szukaj po nazwie, typie lub rozmiarze...">
            </div>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('items.create') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-plus"></i> Dodaj nowy przedmiot
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4">Zdjęcie</th>
                            <th>Nazwa</th>
                            <th>Typ</th>
                            <th>Stan magazynowy</th>
                            <th>Najbliższa ważność</th>
                            <th class="text-end pe-4">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td class="ps-4">
                                    @if ($item->preview_image_path)
                                        <img src="{{ Storage::url($item->preview_image_path) }}"
                                             class="rounded-3 shadow-sm"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold text-dark">{{ $item->name }}</td>
                                <td class="text-muted">{{ $item->type }}</td>
                                <td>
                                    @php $currentStock = $item->total_stock; @endphp
                                    <span class="badge {{ $currentStock > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $currentStock }} szt.
                                    </span>
                                </td>
                                <td>
                                    @php 
                                        $nextExpiry = $item->batches->where('current_quantity', '>', 0)->sortBy('expiration_date')->first();
                                    @endphp
                                    @if($nextExpiry)
                                        <span class="{{ $nextExpiry->expiration_date->isPast() ? 'text-danger fw-bold' : '' }}">
                                            {{ $nextExpiry->expiration_date->format('Y-m-d') }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Brak</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-light text-primary" title="Szczegóły">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-light text-secondary" title="Edytuj">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-search fa-2x mb-3"></i><br>
                                        Nie znaleziono przedmiotów pasujących do "<strong>{{ $search }}</strong>".
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-white border-0 py-3">
            {{ $items->links() }} 
        </div>
    </div>
</div>