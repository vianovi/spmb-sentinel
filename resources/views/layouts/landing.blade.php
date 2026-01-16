<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPMB Sentinel') }} - Pendaftaran Santri Baru</title>

    <!-- Icons & Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/hotel-icon.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Poppins:wght@400;500;600;700&display=swap">
</head>
<body>
    @yield('content')

    <!-- Scripts -->
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>