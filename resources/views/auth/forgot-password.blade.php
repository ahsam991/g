<x-guest-layout>
    <h2 class="text-center font-display text-2xl text-navy">Reset your password</h2>
    <p class="mt-2 text-center text-sm text-muted">Enter your email and we'll send you a reset link.</p>

    <x-auth-session-status class="mt-4 rounded-lg bg-success/10 px-4 py-2 text-sm text-success" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-ink">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="mt-1 block w-full rounded-xl border-line px-4 py-2.5 text-sm shadow-sm focus:border-orange focus:ring-orange">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <button type="submit" class="w-full rounded-xl bg-navy px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-navy/90 active:scale-[.98]">
            Send reset link
        </button>

        <p class="text-center text-sm text-muted">
            <a href="{{ route('login') }}" class="font-medium text-orange hover:text-orange/80">Back to sign in</a>
        </p>
    </form>
</x-guest-layout>
