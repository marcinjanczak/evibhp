@extends('layouts.app')

@section('title', 'Strona główna')

@section('content')
<main>
<div class="start-section">
    <h1>Witaj w systemie zarządzania</h1>
    <div class="links-container">
        <div class="link-box">
            <a href="/employees">
                <i class="fas fa-users"></i>
                <span>Pracownicy</span>
                <span class="description">Zarządzaj listą pracowników</span>
            </a>
        </div>
        <div class="link-box">
            <a href="/items">
                <i class="fas fa-boxes"></i>
                <span>Przedmioty</span>
                <span class="description">Przeglądaj zasoby firmy</span>
            </a>
        </div>
        <div class="link-box">
            <a href="/rentals">
                <i class="fas fa-handshake"></i>
                <span>Wypożyczenia</span>
                <span class="description">Śledź wypożyczenia</span>
            </a>
        </div>
    </div>
</div>

</main>
@endsection