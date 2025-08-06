@extends('layouts.app')

@section('title', 'Strona główna')

@section('content')
<main>
    <h1>Witaj w aplikacji do zarządzania przedmotami</h1>
    <div class="links">
        <div class="links-vievs">
            <a href="/rentals">Zarządzanie wyporzyczeniami</a>
        </div>
        <div class="links-vievs">
            <a href="/employees">Zarządzanie pracownikami</a>
        </div>
        
        <div class="links-vievs">
            <a href="/items">Zarządzanie przedmiotami</a>
        </div>
    </div>
</main>
@endsection