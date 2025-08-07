<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Moja Aplikacja</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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