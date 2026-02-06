@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Magazyn BHP</h2>
        <a href="{{ route('items.create') }}" class="btn btn-success">Dodaj Przedmiot</a>
    </div>

    <table class="table table-hover shadow-sm bg-white rounded">
        <thead class="table-light">
            <tr>
                <th>Zdjęcie</th>
                <th>Nazwa</th>
                <th>Rozmiar</th>
                <th>Stan łączny</th>
                <th>Najbliższa data</th>
                <th class="text-end">Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr>
                <td>
                    <img src="{{ $item->preview_image_path ? Storage::url($item->preview_image_path) : asset('default.png') }}" 
                         style="width: 50px; height: 50px; object-fit: cover" class="rounded">
                </td>
                <td><strong>{{ $item->name }}</strong><br><small class="text-muted">{{ $item->type }}</small></td>
                <td><span class="badge bg-secondary">{{ $item->size }}</span></td>
                <td>
                    @php $total = $item->total_stock; @endphp
                    <span class="badge {{ $total > 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $total }} szt.
                    </span>
                </td>
                <td>
                    @php 
                        $nextBatch = $item->batches->where('current_quantity', '>', 0)->sortBy('expiration_date')->first();
                    @endphp
                    {{ $nextBatch ? $nextBatch->expiration_date->format('Y-m-d') : '---' }}
                </td>
                <td class="text-end">
                    <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                    <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection