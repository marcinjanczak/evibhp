@extends('layouts.app')

@section('title', 'Pojazdy i Wyjazdy')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Pojazdy i Wyjazdy Służbowe</h2>
        <p class="text-muted mb-0">Zarządzaj flotą pojazdów i ewidencjonuj wyjazdy</p>
    </div>
</div>

<ul class="nav nav-tabs mb-4" id="vehiclesTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="trips-tab" data-bs-toggle="tab" data-bs-target="#trips" type="button" role="tab" aria-controls="trips" aria-selected="true">
            <i class="fas fa-route me-1"></i> Lista wyjazdów
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="vehicles-tab" data-bs-toggle="tab" data-bs-target="#vehicles" type="button" role="tab" aria-controls="vehicles" aria-selected="false">
            <i class="fas fa-car me-1"></i> Baza pojazdów
        </button>
    </li>
</ul>

<div class="tab-content" id="vehiclesTabContent">
    <div class="tab-pane fade show active" id="trips" role="tabpanel" aria-labelledby="trips-tab">
        <livewire:vehicles.trip-list />
    </div>
    <div class="tab-pane fade" id="vehicles" role="tabpanel" aria-labelledby="vehicles-tab">
        <livewire:vehicles.vehicle-list />
    </div>
</div>
@endsection
