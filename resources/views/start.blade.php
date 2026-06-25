@extends('layouts.app')

@section('title', 'Strona główna')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center text-center py-5 min-vh-50">
    <h1 class="mb-5 fw-bold text-dark display-5">Witaj w systemie zarządzania odzieżą pracowniczą</h1>
    
    <div class="row g-4 justify-content-center w-100" style="max-width: 1000px;">
        <div class="col-md-4">
            <a href="/employees" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 bg-light transition-hover text-center py-4">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center gap-2">
                        <i class="fas fa-users fa-3x text-primary mb-2"></i>
                        <h4 class="card-title text-dark fw-bold mb-0">Pracownicy</h4>
                        <p class="card-text text-muted small mt-2 mb-0">Zarządzaj listą pracowników</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/items" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 bg-light transition-hover text-center py-4">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center gap-2">
                        <i class="fas fa-boxes fa-3x text-primary mb-2"></i>
                        <h4 class="card-title text-dark fw-bold mb-0">Przedmioty</h4>
                        <p class="card-text text-muted small mt-2 mb-0">Przeglądaj zasoby</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="/issues" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-0 bg-light transition-hover text-center py-4">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center gap-2">
                        <i class="fas fa-handshake fa-3x text-primary mb-2"></i>
                        <h4 class="card-title text-dark fw-bold mb-0">Wydania</h4>
                        <p class="card-text text-muted small mt-2 mb-0">Śledź wydania</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
.transition-hover {
    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
}
.transition-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    background-color: #fff !important;
}
</style>
@endsection