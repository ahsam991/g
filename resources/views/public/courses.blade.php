<x-layouts.public title="Find Your Course" description="Search courses by subject, level, country and intake.">
    <section class="bg-gradient-to-b from-canvas to-white pt-24 pb-12 sm:pt-32 sm:pb-16">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
            <h1 class="font-display text-3xl text-navy sm:text-4xl md:text-5xl">Find your course</h1>
            <p class="mt-4 text-base text-muted sm:mt-5 sm:text-lg">Tell us what you want to study. We will match you to the right universities.</p>
        </div>
        <div class="mx-auto mt-8 max-w-4xl px-4 sm:mt-10 sm:px-6">
            <form action="{{ route('contact') }}" class="rounded-2xl border border-white/40 bg-white/70 p-4 shadow-glass backdrop-blur-xl sm:p-5">
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <select name="subject" class="rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                        <option value="">Subject</option>
                        @foreach(['Business', 'Computer Science', 'Engineering', 'Nursing & Health', 'Law', 'Arts'] as $s)
                            <option>{{ $s }}</option>
                        @endforeach
                    </select>
                    <select name="level" class="rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                        <option value="">Level</option>
                        @foreach(['Foundation', 'Undergraduate', 'Pre-Masters', 'Masters', 'PhD'] as $l)
                            <option>{{ $l }}</option>
                        @endforeach
                    </select>
                    <select name="country" class="rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                        <option value="">Country</option>
                        @foreach($destinations as $dest)
                            <option>{{ $dest->name }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-xl bg-navy px-5 py-2.5 text-sm font-semibold text-white transition-transform active:scale-[.98]">Search</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 sm:pb-20">
        @foreach($destinations as $dest)
            @if($dest->universities->isNotEmpty())
            <div class="mb-10" x-data x-intersect.once="$el.classList.add('animate-rise')">
                <div class="mb-6 flex items-center gap-3 opacity-0" style="animation-delay: 100ms">
                    <span class="text-3xl">{{ $dest->flag }}</span>
                    <h2 class="font-display text-2xl text-navy sm:text-3xl">{{ $dest->name }}</h2>
                    <span class="rounded-full bg-navy/5 px-3 py-1 text-xs font-medium text-navy">{{ $dest->universities->count() }} {{ Str::plural('university', $dest->universities->count()) }}</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($dest->universities as $uni)
                        <div class="group rounded-2xl border border-line bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:border-orange/30">
                            <div class="flex items-start gap-3">
                                @if($uni->logo)
                                    <img src="{{ asset('storage/' . $uni->logo) }}" alt="" class="h-10 w-10 rounded-lg object-contain">
                                @else
                                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-navy/5 text-sm font-bold text-navy">{{ Str::substr($uni->name, 0, 2) }}</div>
                                @endif
                                <div>
                                    <h3 class="font-semibold text-navy group-hover:text-orange transition-colors">{{ $uni->name }}</h3>
                                    @if($uni->city)
                                        <p class="text-xs text-muted">{{ $uni->city }}</p>
                                    @endif
                                </div>
                            </div>
                            @if($uni->ranking)
                                <span class="mt-3 inline-flex rounded-full bg-orange/10 px-2.5 py-0.5 text-xs font-medium text-orange">{{ $uni->ranking }}</span>
                            @endif
                            @if($uni->short_description)
                                <p class="mt-3 text-sm text-muted">{{ $uni->short_description }}</p>
                            @endif
                            @if($uni->courses->isNotEmpty())
                                <div class="mt-4 border-t border-line pt-3">
                                    <p class="text-xs font-medium text-muted">{{ $uni->courses->count() }} {{ Str::plural('course', $uni->courses->count()) }} available</p>
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach($uni->courses->take(4) as $course)
                                            <span class="rounded-full bg-canvas px-2.5 py-0.5 text-xs text-ink">{{ $course->name }}</span>
                                        @endforeach
                                        @if($uni->courses->count() > 4)
                                            <span class="rounded-full bg-canvas px-2.5 py-0.5 text-xs text-muted">+{{ $uni->courses->count() - 4 }} more</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @if($uni->website)
                                <a href="{{ $uni->website }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1 text-xs font-medium text-navy hover:text-orange transition-colors">
                                    Visit website
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

        @if($destinations->flatMap->universities->isEmpty())
        <div class="rounded-2xl border border-dashed border-line bg-canvas p-6 text-center sm:p-10">
            <h2 class="font-display text-xl text-navy sm:text-2xl">Universities being added</h2>
            <p class="mx-auto mt-3 max-w-lg text-sm text-muted">Book a free counselling session and a certified adviser will shortlist courses matched to your grades and budget.</p>
            <a href="{{ route('contact') }}" class="mt-6 inline-block rounded-full bg-orange px-7 py-3 font-semibold text-navy transition-transform active:scale-[.98]">Book Free Counselling</a>
        </div>
        @endif
    </section>
</x-layouts.public>
