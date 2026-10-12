<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in &middot; {{ settings('company.name', config('app.name')) }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Instrument+Serif:wght@400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-navy font-sans text-ink antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center px-4">
        {{-- Logo --}}
        <a href="/" class="mb-8 flex items-center gap-3">
            <span class="grid h-12 w-12 place-items-center rounded-full bg-orange font-display text-2xl text-navy">G</span>
            <span class="font-display text-2xl leading-none text-white">Global<br><span class="text-orange">Consultancy</span></span>
        </a>

        {{-- Card --}}
        <div class="w-full max-w-md rounded-2xl border border-white/10 bg-white p-8 shadow-xl">
            {{ $slot }}
        </div>

        <p class="mt-6 text-center text-xs text-white/40">&copy; {{ date('Y') }} Global Consultancy Education. All rights reserved.</p>
    </div>
</body>
</html>
