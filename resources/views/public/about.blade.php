<x-layouts.public title="About Us" description="A reputable education consultancy offering end-to-end support, from first consultation to settling in abroad.">
    <section class="bg-gradient-to-b from-canvas to-white pt-24 pb-12 sm:pt-32 sm:pb-16">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
            <span class="text-sm font-semibold uppercase tracking-wide text-orange">Who we are</span>
            <h1 class="mt-3 font-display text-3xl text-navy sm:text-4xl md:text-5xl">Guiding students to study abroad with confidence</h1>
            <p class="mt-4 text-base text-muted sm:mt-5 sm:text-lg">A reputable education consultancy offering end-to-end support, from your first consultation to settling in overseas. British Council certified, with a focus on quality over quantity.</p>
        </div>
    </section>
    <section class="mx-auto max-w-5xl px-4 pb-16 sm:px-6 sm:pb-20">
        <div class="grid gap-4 sm:gap-6 md:grid-cols-3">
            @foreach([
                ['Quality over quantity', 'We take fewer students so each one gets genuine attention and the highest chance of success.'],
                ['Certified counsellors', 'British Council certified advisers who put your individual needs first.'],
                ['End-to-end support', 'Research, applications, visa prep, pre-departure and post-arrival care.'],
            ] as $card)
                <div class="rounded-2xl border border-line bg-white p-5 shadow-glass sm:p-7">
                    <h3 class="font-display text-lg text-navy sm:text-xl">{{ $card[0] }}</h3>
                    <p class="mt-2 text-sm text-muted">{{ $card[1] }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-8 grid gap-6 rounded-2xl border border-line bg-canvas p-5 sm:mt-10 sm:grid-cols-2 sm:p-8">
            <div>
                <h3 class="font-display text-2xl text-navy">London Office</h3>
                <p class="mt-2 text-sm text-muted">{{ settings('company.address_london') }}</p>
                <p class="mt-1 text-sm text-navy">{{ settings('company.phone_uk') }}</p>
            </div>
            <div>
                <h3 class="font-display text-2xl text-navy">Khulna Office</h3>
                <p class="mt-2 text-sm text-muted">{{ settings('company.address_khulna') }}</p>
                <p class="mt-1 text-sm text-navy">{{ settings('company.phone_bd_1') }}</p>
            </div>
        </div>
    </section>
</x-layouts.public>
