<x-layouts.public title="Study Destinations" description="Study in the UK, USA, Canada, Australia, Europe, Malaysia and more.">
    <section class="bg-gradient-to-b from-canvas to-white pt-24 pb-12 sm:pt-32 sm:pb-16">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
            <h1 class="font-display text-3xl text-navy sm:text-4xl md:text-5xl">Where will you study?</h1>
            <p class="mt-4 text-base text-muted sm:mt-5 sm:text-lg">{{ $destinations->count() }}+ destinations, each with trusted partner universities and proven visa routes.</p>
        </div>
    </section>
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 sm:pb-20">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
            @foreach($destinations as $dest)
                <div x-data x-intersect.once="$el.classList.add('animate-rise')" class="group overflow-hidden rounded-2xl border border-line bg-white opacity-0 shadow-glass transition-all duration-300 ease-rise hover:-translate-y-1" style="animation-delay: {{ $loop->index * 80 }}ms">
                    @if($dest->image)
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset('storage/' . $dest->image) }}" alt="{{ $dest->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-navy/60 to-transparent"></div>
                            <span class="absolute bottom-4 left-4 text-4xl">{{ $dest->flag }}</span>
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-navy to-navy-900 p-8 text-center text-6xl">{{ $dest->flag }}</div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="font-display text-2xl text-navy group-hover:text-orange transition-colors">{{ $dest->name }}</h3>
                            @if($dest->universities_count)
                                <span class="rounded-full bg-navy/5 px-3 py-1 text-xs font-medium text-navy">{{ $dest->universities_count }} {{ Str::plural('university', $dest->universities_count) }}</span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm text-muted">{{ $dest->short_description }}</p>
                        @if($dest->description)
                            <p class="mt-2 text-sm text-muted">{{ Str::limit($dest->description, 150) }}</p>
                        @endif
                        <a href="{{ route('contact') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-orange hover:underline transition-transform hover:translate-x-1">
                            Ask about {{ $dest->name }}
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts.public>
