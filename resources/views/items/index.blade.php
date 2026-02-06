@extends('layouts.app')

@section('title', 'Lista Przedmiotów')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Przedmioty</h2>
    </div>

    <livewire:productsComponents.productTable />
    
</div>
@endsection