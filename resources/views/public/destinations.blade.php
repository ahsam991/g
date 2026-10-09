<x-layouts.public title="Study Destinations" description="Study in the UK, USA, Canada, Australia, Europe, Malaysia and more.">
    <section class="bg-gradient-to-b from-canvas to-white pt-24 pb-12 sm:pt-32 sm:pb-16">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
            <h1 class="font-display text-3xl text-navy sm:text-4xl md:text-5xl">Where will you study?</h1>
            <p class="mt-4 text-base text-muted sm:mt-5 sm:text-lg">15+ destinations, each with trusted partner universities and proven visa routes.</p>
        </div>
    </section>
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 sm:pb-20">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
            @foreach([
                ['United Kingdom', '🇬🇧', 'Russell Group universities, 2-year post-study work visa, world-ranked degrees.'],
                ['USA', '🇺🇸', 'Ivy League and state universities, OPT work options, scholarships available.'],
                ['Canada', '🇨🇦', 'Affordable tuition, work while you study, a clear path to residency.'],
                ['Australia', '🇦🇺', 'Globally recognised degrees, generous post-study work rights.'],
                ['Europe', '🇪🇺', 'Low or no tuition in many countries, English-taught programmes.'],
                ['Malaysia', '🇲🇾', 'Global campuses at a fraction of the cost, a gateway to Asia.'],
            ] as $dest)
                <div class="group overflow-hidden rounded-2xl border border-line bg-white shadow-glass transition-all duration-300 ease-rise hover:-translate-y-1">
                    <div class="bg-gradient-to-br from-navy to-navy-900 p-8 text-center text-6xl">{!! $dest[1] !!}</div>
                    <div class="p-6">
                        <h3 class="font-display text-2xl text-navy group-hover:text-orange">{{ $dest[0] }}</h3>
                        <p class="mt-2 text-sm text-muted">{{ $dest[2] }}</p>
                        <a href="{{ route('contact') }}" class="mt-4 inline-block text-sm font-semibold text-orange hover:underline">Ask about {{ $dest[0] }} →</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts.public>
