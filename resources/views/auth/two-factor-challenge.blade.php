<x-guest-layout>
    <h1 class="font-display text-3xl text-navy">One more step</h1>

    <p class="mt-2 text-sm text-muted">
        Enter the code from your authenticator app, or one of your recovery codes.
    </p>

    <form method="POST" action="{{ route('two-factor.challenge.store') }}" class="mt-6">
        @csrf
        <x-input-label for="code" value="Code" />
        <x-text-input id="code" name="code" type="text" autocomplete="one-time-code"
                      class="mt-1 block w-full" required autofocus />
        <x-input-error :messages="$errors->get('code')" class="mt-2" />

        <x-primary-button class="mt-4">Verify</x-primary-button>
    </form>
</x-guest-layout>
