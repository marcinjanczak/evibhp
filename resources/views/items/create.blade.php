@extends('layouts.app')

@section('title', 'Wydaj Przedmiot')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h3 class="mb-0">Wydanie towaru</h3>
                    </div>
                    <div class="card-body">
                        
                        {{-- TU JEST MAGIA LIVEWIRE --}}
                        <livewire:issue-form />

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection