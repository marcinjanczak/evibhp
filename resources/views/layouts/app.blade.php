<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Moja Aplikacja</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <!-- Wstawiamy nagłówek -->
    @include('partials.header')

    <!-- Główna zawartość -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Wstawiamy stopkę -->
    @include('partials.footer')
</body>
</html>