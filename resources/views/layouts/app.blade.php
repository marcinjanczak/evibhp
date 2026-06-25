<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Evibhp Planteon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <!-- Wstawiamy nagłówek -->
    @include('partials.header')

    <!-- Główna zawartość -->
    <main class="main-content">
        <div class="container py-4">
            @yield('content')
        </div>
    </main>

    <!-- Wstawiamy stopkę -->
    @include('partials.footer')

    <!-- Powiadomienia (Snackbar/Toast) -->
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition.opacity.duration.500ms
             class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
            <div class="toast align-items-center text-bg-success border-0 show shadow-lg" role="alert">
                <div class="d-flex">
                    <div class="toast-body fw-medium">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    <button type="button" @click="show = false" class="btn-close btn-close-white me-2 m-auto"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity.duration.500ms
             class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
            <div class="toast align-items-center text-bg-danger border-0 show shadow-lg" role="alert">
                <div class="d-flex">
                    <div class="toast-body fw-medium">
                        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    </div>
                    <button type="button" @click="show = false" class="btn-close btn-close-white me-2 m-auto"></button>
                </div>
            </div>
        </div>
    @endif

    @livewireScripts

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>