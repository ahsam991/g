<x-guest-layout>
    <h1 class="font-display text-3xl text-navy">Two-factor is on</h1>

    @if (count($recoveryCodes))
        <p class="mt-2 text-sm text-muted">
            Save these recovery codes somewhere safe. Each one works once, and they are
            not shown again.
        </p>

        <ul class="mt-4 grid grid-cols-2 gap-2 rounded-xl border border-line bg-canvas p-4 font-mono text-sm">
            @foreach ($recoveryCodes as $code)
                <li>{{ $code }}</li>
            @endforeach
        </ul>
    @else
        <p class="mt-2 text-sm text-muted">Your account already requires a second factor at sign-in.</p>
    @endif

    <a href="{{ route('dashboard') }}" class="mt-6 inline-block text-sm font-medium text-navy underline">
        Continue to dashboard
    </a>
</x-guest-layout>
