<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'CRM' }} &middot; {{ settings('company.name', config('app.name')) }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Instrument+Serif:wght@400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-canvas font-sans text-ink antialiased">
<div x-data="{ sidebar: false }" class="min-h-full lg:flex">

    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-40 w-[280px] -translate-x-full bg-navy text-white transition-transform duration-300 ease-rise lg:static lg:translate-x-0"
        :class="sidebar && 'translate-x-0'"
    >
        <div class="flex h-16 items-center gap-3 px-6">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-orange font-display text-lg text-navy">G</span>
            <span class="font-display text-xl leading-none">Global<br><span class="text-orange">Consultancy</span></span>
        </div>

        <nav class="mt-4 space-y-1 px-3 pb-8" aria-label="Main">
            <x-crm.nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-crm.nav-link>

            @can('leads.view')
                <x-crm.nav-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">Leads</x-crm.nav-link>
            @endcan
            @can('candidates.view')
                <x-crm.nav-link :href="route('candidates.index')" :active="request()->routeIs('candidates.*')">Students</x-crm.nav-link>
            @endcan
            @can('applications.view')
                <x-crm.nav-link href="#" :active="request()->routeIs('applications.*')">Applications</x-crm.nav-link>
            @endcan
            @can('documents.view')
                <x-crm.nav-link href="#" :active="request()->routeIs('documents.*')">Documents</x-crm.nav-link>
            @endcan
            @can('catalogue.view')
                <x-crm.nav-link href="#" :active="request()->routeIs('catalogue.*')">Universities</x-crm.nav-link>
            @endcan
            @can('appointments.manage')
                <x-crm.nav-link href="#" :active="request()->routeIs('appointments.*')">Calendar</x-crm.nav-link>
            @endcan
            @canany(['finance.view.all', 'finance.view.own'])
                <x-crm.nav-link href="#" :active="request()->routeIs('finance.*')">Finance</x-crm.nav-link>
            @endcanany
            @can('reports.view')
                <x-crm.nav-link href="#" :active="request()->routeIs('reports.*')">Reports</x-crm.nav-link>
            @endcan

            @can('users.manage')
                <p class="px-3 pb-1 pt-6 text-xs uppercase tracking-wider text-white/40">Administration</p>
                <x-crm.nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">Users</x-crm.nav-link>
                <x-crm.nav-link :href="route('admin.roles.index')" :active="request()->routeIs('admin.roles.*')">Roles</x-crm.nav-link>
                <x-crm.nav-link :href="route('admin.branches.index')" :active="request()->routeIs('admin.branches.*')">Branches</x-crm.nav-link>
            @endcan
            @can('settings.manage')
                <x-crm.nav-link :href="route('admin.settings.edit')" :active="request()->routeIs('admin.settings.*')">Settings</x-crm.nav-link>
            @endcan
            @can('audit.view')
                <x-crm.nav-link :href="route('admin.audit.index')" :active="request()->routeIs('admin.audit.*')">Audit log</x-crm.nav-link>
            @endcan
        </nav>
    </aside>

    {{-- Scrim for the mobile drawer --}}
    <div x-show="sidebar" x-cloak @click="sidebar = false"
         class="fixed inset-0 z-30 bg-navy/40 backdrop-blur-sm lg:hidden"></div>

    <div class="flex min-w-0 flex-1 flex-col">
        {{-- Topbar --}}
        <header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-line bg-white/70 px-4 backdrop-blur-xl sm:px-6">
            <button @click="sidebar = !sidebar" class="lg:hidden" aria-label="Toggle navigation">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/>
                </svg>
            </button>

            <div class="min-w-0 flex-1">
                <h1 class="truncate font-display text-2xl text-navy">{{ $title ?? 'Dashboard' }}</h1>
            </div>

            <div x-data="{ menu: false }" class="relative">
                <button @click="menu = !menu" class="flex items-center gap-2 rounded-xl px-2 py-1.5 hover:bg-canvas">
                    <span class="grid h-8 w-8 place-items-center rounded-full bg-navy text-sm font-medium text-white">
                        {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <span class="hidden text-sm font-medium sm:block">{{ auth()->user()->name }}</span>
                </button>

                <div x-show="menu" x-cloak @click.outside="menu = false"
                     class="absolute right-0 mt-2 w-56 rounded-xl border border-line bg-white p-1 shadow-glass">
                    <p class="px-3 py-2 text-xs text-muted">{{ auth()->user()->role?->name ?? 'No role' }}</p>
                    <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-canvas">Profile</a>
                    <a href="{{ route('two-factor.show') }}" class="block rounded-lg px-3 py-2 text-sm hover:bg-canvas">Two-factor</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="block w-full rounded-lg px-3 py-2 text-left text-sm text-danger hover:bg-canvas">Sign out</button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6">
            @if (session('status'))
                <div class="mb-4 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-sm text-ink">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
