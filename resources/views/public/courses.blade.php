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
                        @foreach(['Business', 'Computer Science', 'Engineering', 'Nursing &amp; Health', 'Law', 'Arts'] as $s)
                            <option>{!! $s !!}</option>
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
                        @foreach(['United Kingdom', 'USA', 'Canada', 'Australia', 'Europe', 'Malaysia'] as $c)
                            <option>{{ $c }}</option>
                        @endforeach
                    </select>
                    <button class="rounded-xl bg-navy px-5 py-2.5 text-sm font-semibold text-white transition-transform active:scale-[.98]">Search</button>
                </div>
            </form>
        </div>
    </section>
    <section class="mx-auto max-w-5xl px-4 pb-16 sm:px-6 sm:pb-20">
        <div class="rounded-2xl border border-dashed border-line bg-canvas p-6 text-center sm:p-10">
            <h2 class="font-display text-xl text-navy sm:text-2xl">Our course database is being loaded</h2>
            <p class="mx-auto mt-3 max-w-lg text-sm text-muted sm:text-base">We work with 50+ universities across 15+ countries. Book a free counselling session and a certified adviser will shortlist courses matched to your grades and budget.</p>
            <a href="{{ route('contact') }}" class="mt-6 inline-block rounded-full bg-orange px-7 py-3 font-semibold text-navy transition-transform active:scale-[.98]">Book Free Counselling</a>
        </div>
    </section>
</x-layouts.public>
