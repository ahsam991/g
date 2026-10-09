<header x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 20"
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
        :class="scrolled ? 'bg-white/80 backdrop-blur-xl shadow-glass' : 'bg-transparent'">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 sm:py-4" aria-label="Main">
        <x-public.logo class="shrink-0" />

        <div class="hidden items-center gap-7 lg:flex">
            @foreach(['home' => 'Home', 'about' => 'About', 'destinations' => 'Destinations', 'courses' => 'Courses', 'services' => 'Services', 'contact' => 'Contact'] as $route => $label)
                <a href="{{ route($route) }}"
                   class="text-sm font-medium transition-colors hover:text-orange {{ request()->routeIs($route) ? 'text-orange' : 'text-ink' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="{{ route('login') }}" class="text-sm font-medium text-ink hover:text-orange">Sign in</a>
            <a href="{{ route('contact') }}"
               class="rounded-full bg-navy px-5 py-2.5 text-sm font-semibold text-white transition-transform hover:bg-navy-700 active:scale-[.98]">
                Free Counselling
            </a>
        </div>

        <button @click="open = !open" class="lg:hidden" aria-label="Menu">
            <svg class="h-6 w-6 text-navy" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path x-show="!open" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/>
                <path x-show="open" x-cloak d="M6 18L18 6M6 6l12 12" stroke-linecap="round"/>
            </svg>
        </button>
    </nav>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         @click.outside="open = false" class="border-t border-line bg-white lg:hidden">
        <div class="space-y-1 px-4 py-3">
            @foreach(['home' => 'Home', 'about' => 'About', 'destinations' => 'Destinations', 'courses' => 'Courses', 'services' => 'Services', 'contact' => 'Contact'] as $route => $label)
                <a href="{{ route($route) }}" class="block rounded-lg px-3 py-3 text-base font-medium hover:bg-canvas {{ request()->routeIs($route) ? 'text-orange' : 'text-ink' }}">{{ $label }}</a>
            @endforeach
            <a href="{{ route('contact') }}" class="mt-3 block rounded-full bg-navy px-5 py-3 text-center text-base font-semibold text-white">Free Counselling</a>
        </div>
    </div>
</header>
