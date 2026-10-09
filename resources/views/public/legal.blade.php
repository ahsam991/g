<x-layouts.public :title="$title">
    <section class="bg-gradient-to-b from-canvas to-white pt-24 pb-8 sm:pt-32 sm:pb-10">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <h1 class="font-display text-3xl text-navy sm:text-4xl">{{ $title }}</h1>
            <p class="mt-2 text-sm text-muted">Last updated {{ date('F Y') }}. Policy version {{ settings('gdpr.policy_version', '1.0') }}.</p>
        </div>
    </section>
    <section class="mx-auto max-w-3xl px-4 pb-16 sm:px-6 sm:pb-24">
        <div class="prose-sm space-y-4 text-muted [&_h2]:mt-8 [&_h2]:font-display [&_h2]:text-xl [&_h2]:text-navy">
            {!! $body !!}
        </div>
    </section>
</x-layouts.public>
