<x-layouts.public title="Our Services" description="Free counselling, admission support, visa compliance, accommodation and more.">
    <section class="bg-gradient-to-b from-canvas to-white pt-24 pb-12 sm:pt-32 sm:pb-16">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
            <h1 class="font-display text-3xl text-navy sm:text-4xl md:text-5xl">Everything you need, in one place</h1>
            <p class="mt-4 text-base text-muted sm:mt-5 sm:text-lg">From the first conversation to your first day on campus, we handle it all.</p>
        </div>
    </section>
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 sm:pb-20">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
            @foreach($services as $i => $service)
                <div x-data x-intersect.once="$el.classList.add('animate-rise')" class="group rounded-2xl border border-line bg-white p-7 opacity-0 shadow-glass transition-all duration-300 ease-rise hover:-translate-y-1.5 hover:shadow-lg hover:border-orange/30" style="animation-delay: {{ $i * 80 }}ms">
                    @if($service->icon_svg)
                    <div class="mb-3 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-navy/5 text-navy transition-colors group-hover:bg-orange group-hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $service->icon_svg }}"/></svg>
                    </div>
                    @endif
                    <div class="h-1 w-10 rounded-full bg-line transition-all duration-300 group-hover:w-16 group-hover:bg-orange"></div>
                    <h3 class="mt-4 font-display text-xl text-navy">{{ $service->title }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-muted">{{ $service->short_description }}</p>
                    @if($service->description)
                        <p class="mt-3 text-sm text-muted">{{ Str::limit($service->description, 200) }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="relative overflow-hidden bg-navy py-14 sm:py-20">
        <div class="pointer-events-none absolute -left-24 top-1/2 h-72 w-72 -translate-y-1/2 rounded-full bg-orange/20 blur-3xl animate-pulse-slow"></div>
        <div class="relative mx-auto max-w-3xl px-4 text-center sm:px-6">
            <h2 class="font-display text-3xl text-white sm:text-4xl">Need help choosing the right service?</h2>
            <p class="mt-3 text-base text-white/70 sm:text-lg">Book a free counselling session and we will map out your path together.</p>
            <a href="{{ route('contact') }}" class="mt-8 inline-flex items-center gap-2 rounded-full bg-orange px-8 py-4 font-semibold text-navy shadow-lg transition-all duration-300 hover:bg-orange-600 hover:shadow-xl active:scale-[.98]">
                Book Free Counselling
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
            </a>
        </div>
    </section>
</x-layouts.public>
