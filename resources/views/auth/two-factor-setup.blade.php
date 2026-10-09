<x-guest-layout>
    <h1 class="font-display text-3xl text-navy">Protect your account</h1>

    <p class="mt-2 text-sm text-muted">
        Scan this with an authenticator app, then enter the six-digit code it shows.
    </p>

    <div class="mt-4 rounded-xl border border-line bg-canvas p-4">
        <p class="text-xs uppercase tracking-wide text-muted">Setup key</p>
        <p class="mt-1 break-all font-mono text-sm text-ink">{{ $secret }}</p>
    </div>

    <form method="POST" action="{{ route('two-factor.store') }}" class="mt-6">
        @csrf
        <x-input-label for="code" value="Six-digit code" />
        <x-text-input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code"
                      class="mt-1 block w-full" required autofocus />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />

        <x-primary-button class="mt-4">Turn on two-factor</x-primary-button>
    </form>
</x-guest-layout>
