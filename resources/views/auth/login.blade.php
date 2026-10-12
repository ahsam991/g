<x-guest-layout>
    <h2 class="text-center font-display text-2xl text-navy">Sign in to your account</h2>
    <p class="mt-1 text-center text-sm text-muted">Enter your credentials to access the CRM</p>

    <x-auth-session-status class="mt-4 rounded-lg bg-success/10 px-4 py-2 text-sm text-success" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-ink">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="mt-1 block w-full rounded-xl border-line px-4 py-2.5 text-sm shadow-sm focus:border-orange focus:ring-orange">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-ink">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="mt-1 block w-full rounded-xl border-line px-4 py-2.5 text-sm shadow-sm focus:border-orange focus:ring-orange">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember" class="rounded border-line text-orange shadow-sm focus:ring-orange">
                <span class="text-sm text-muted">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-orange hover:text-orange/80">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="w-full rounded-xl bg-navy px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-navy/90 active:scale-[.98]">
            Sign in
        </button>
    </form>
</x-guest-layout>
