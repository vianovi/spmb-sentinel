<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SPMB Sentinel') }}</title>

    {{-- Fonts (biar senada sama pre-registration UI) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@500;600;700&display=swap"
        rel="stylesheet">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        .font-serif-sentinel {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-950 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 text-slate-900 antialiased">
    {{-- Glow background (global untuk guest pages) --}}
    <div class="pointer-events-none fixed -top-24 -right-16 w-72 h-72 bg-gradient-to-br from-amber-400/40 via-rose-400/30 to-sky-500/30 rounded-full blur-3xl opacity-70"></div>
    <div class="pointer-events-none fixed -bottom-24 -left-16 w-80 h-80 bg-gradient-to-tr from-emerald-400/30 via-cyan-400/30 to-amber-300/30 rounded-full blur-3xl opacity-70"></div>

    {{ $slot }}
</body>
</html>
