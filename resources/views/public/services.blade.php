<x-layouts.public title="Our Services" description="Free counselling, admission support, visa compliance, accommodation and more.">
    <section class="bg-gradient-to-b from-canvas to-white pt-24 pb-12 sm:pt-32 sm:pb-16">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
            <h1 class="font-display text-3xl text-navy sm:text-4xl md:text-5xl">Everything you need, in one place</h1>
            <p class="mt-4 text-base text-muted sm:mt-5 sm:text-lg">From the first conversation to your first day on campus, we handle it all.</p>
        </div>
    </section>
    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 sm:pb-20">
        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
            @foreach([
                ['Free Counselling', 'Honest, no-pressure guidance to find the right country, course and university for you.'],
                ['Admission Support', 'We shortlist courses, prepare your application and chase every deadline.'],
                ['Visa Compliance', 'Document checks, SOP guidance and interview preparation for a strong application.'],
                ['Student Accommodation', 'Verified housing arranged before you fly, close to your campus.'],
                ['Partner Universities', 'Direct relationships with 50+ institutions across 15+ countries.'],
                ['IELTS &amp; English', 'Training and preparation to meet your language requirements.'],
                ['Scholarship Guidance', 'We identify and apply for the funding you are eligible for.'],
                ['Pre-Departure Briefing', 'Flights, insurance, packing and what to expect on arrival.'],
                ['Post-Arrival Support', 'BRP, bank accounts and settling in once you land.'],
            ] as $service)
                <div class="group rounded-2xl border border-line bg-white p-7 shadow-glass transition-all duration-300 ease-rise hover:-translate-y-1">
                    <div class="h-1 w-10 rounded-full bg-line transition-colors group-hover:bg-orange"></div>
                    <h3 class="mt-4 font-display text-xl text-navy">{!! $service[0] !!}</h3>
                    <p class="mt-2 text-sm text-muted">{{ $service[1] }}</p>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts.public>
