@extends('layouts.app')

@section('title', 'Przedmioty')

@section('content')
<main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista przedmiotów</h2>
        <a href="{{ route('items.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Dodaj nowy przedmiot
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Zdjęcie</th>
                        <th>Nazwa</th>
                        <th>Typ</th>
                        <th>Rozmiar</th>
                        <th>Ilość w magazynie</th>
                        <th>Data używalności</th>
                        <th class="text-end">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>
                                @if ($item->preview_image_path)
                                    <img src="{{ Storage::url($item->preview_image_path) }}"
                                         alt="{{ $item->name }}" class="img-thumbnail"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center img-thumbnail" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-box text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $item->name }}</td>
                            <td>{{ $item->type }}</td>
                            <td><span class="badge bg-secondary">{{ $item->size }}</span></td>
                            <td>
                                @php $stock = $item->inventory->quantity ?? 0; @endphp
                                <span class="badge {{ $stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $stock }} szt.
                                </span>
                            </td>
                            <td>
                                {{ $item->expiration_date ? $item->expiration_date->format('Y-m-d') : 'Brak' }}
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end px-2">
                                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-outline-info" title="Szczegóły">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Edytuj">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Czy na pewno usunąć przedmiot {{ $item->name }}?')" title="Usuń">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Brak przedmiotów w magazynie.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection