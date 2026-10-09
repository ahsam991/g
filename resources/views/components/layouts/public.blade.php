<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $title ?? 'Study Abroad Consultancy' }} &middot; {{ settings('company.name', 'Global Consultancy') }}</title>
    <meta name="description" content="{{ $description ?? settings('company.tagline') }}">

    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? settings('company.name') }}">
    <meta property="og:description" content="{{ $description ?? settings('company.tagline') }}">
    <meta property="og:type" content="website">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "EducationalOrganization",
        "name": "{{ settings('company.name') }}",
        "url": "{{ settings('company.website') }}",
        "email": "{{ settings('company.email') }}",
        "telephone": "{{ settings('company.phone_uk') }}",
        "sameAs": ["{{ settings('social.facebook') }}"]
    }
    </script>
</head>
<body class="bg-white font-sans text-ink antialiased">
<a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-navy focus:px-4 focus:py-2 focus:text-white">Skip to content</a>

<x-public.header />

<main id="main">
    {{ $slot }}
</main>

<x-public.footer />

@stack('scripts')
</body>
</html>
