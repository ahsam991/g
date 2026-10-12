<x-guest-layout>
    <h2 class="text-center font-display text-2xl text-navy">Create your account</h2>
    <p class="mt-1 text-center text-sm text-muted">Register to access the student portal</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-ink">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="mt-1 block w-full rounded-xl border-line px-4 py-2.5 text-sm shadow-sm focus:border-orange focus:ring-orange">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-ink">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="mt-1 block w-full rounded-xl border-line px-4 py-2.5 text-sm shadow-sm focus:border-orange focus:ring-orange">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-ink">Phone number</label>
            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                   class="mt-1 block w-full rounded-xl border-line px-4 py-2.5 text-sm shadow-sm focus:border-orange focus:ring-orange"
                   placeholder="+44 7XXX XXXXXX">
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-ink">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="mt-1 block w-full rounded-xl border-line px-4 py-2.5 text-sm shadow-sm focus:border-orange focus:ring-orange">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-ink">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="mt-1 block w-full rounded-xl border-line px-4 py-2.5 text-sm shadow-sm focus:border-orange focus:ring-orange">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <button type="submit" class="w-full rounded-xl bg-navy px-6 py-3 text-sm font-semibold text-white shadow-sm transition-all hover:bg-navy/90 active:scale-[.98]">
            Create account
        </button>

        <p class="text-center text-sm text-muted">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-orange hover:text-orange/80">Sign in</a>
        </p>
    </form>
</x-guest-layout>
