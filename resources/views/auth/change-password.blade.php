<x-guest-layout>
    <h1 class="font-display text-3xl text-navy">Choose a new password</h1>

    <p class="mt-2 text-sm text-muted">
        Your account is using a temporary password. Pick your own to continue.
        Use at least 12 characters with upper and lower case letters and a number.
    </p>

    <form method="POST" action="{{ route('password.change.store') }}" class="mt-6">
        @csrf
        <x-input-label for="password" value="New password" />
        <x-text-input id="password" name="password" type="password" autocomplete="new-password"
                      class="mt-1 block w-full" required autofocus />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />

        <x-input-label for="password_confirmation" value="Confirm new password" class="mt-4" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                      autocomplete="new-password" class="mt-1 block w-full" required />

        <x-primary-button class="mt-4">Save password</x-primary-button>
    </form>
</x-guest-layout>
