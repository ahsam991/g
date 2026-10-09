<x-layouts.public title="Contact Us" description="Book a free counselling session. Offices in London and Khulna.">
    <section class="bg-gradient-to-b from-canvas to-white pt-24 pb-12 sm:pt-32 sm:pb-16">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6">
            <h1 class="font-display text-3xl text-navy sm:text-4xl md:text-5xl">Let's talk about your future</h1>
            <p class="mt-4 text-base text-muted sm:mt-5 sm:text-lg">Book a free counselling session. No cost, no obligation.</p>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 pb-16 sm:px-6 sm:pb-24">
        <div class="grid gap-8 lg:grid-cols-5 lg:gap-10">
            <div class="lg:col-span-3">
                @if(session('enquiry_sent'))
                    <div class="mb-6 rounded-2xl border border-success/30 bg-success/10 px-5 py-4">
                        <p class="font-semibold text-navy">Thank you. We have your enquiry.</p>
                        <p class="mt-1 text-sm text-muted">A counsellor will be in touch within one working day.</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('enquiry.store') }}" class="rounded-2xl border border-line bg-white p-7 shadow-glass">
                    @csrf
                    {{-- Honeypot: hidden from people, tempting to bots --}}
                    <div class="hidden" aria-hidden="true">
                        <label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <x-input-label for="name" value="Full name" />
                            <x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name')" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone (with country code)" />
                            <x-text-input id="phone" name="phone" class="mt-1 block w-full" :value="old('phone')" />
                        </div>
                        <div>
                            <x-input-label for="country" value="Preferred destination" />
                            <select id="country" name="country" class="mt-1 block w-full rounded-xl border-line focus:border-orange focus:ring-orange">
                                <option value="">Choose a country</option>
                                @foreach(['United Kingdom', 'USA', 'Canada', 'Australia', 'Europe', 'Malaysia', 'Other'] as $c)
                                    <option @selected(old('country') === $c)>{{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-5">
                        <x-input-label for="message" value="How can we help?" />
                        <textarea id="message" name="message" rows="4" class="mt-1 block w-full rounded-xl border-line focus:border-orange focus:ring-orange">{{ old('message') }}</textarea>
                    </div>

                    <button class="mt-6 rounded-full bg-navy px-8 py-3.5 font-semibold text-white transition-transform hover:bg-navy-700 active:scale-[.98]">Send enquiry</button>
                </form>
            </div>

            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-line bg-canvas p-6">
                    <h3 class="font-display text-xl text-navy">London Office</h3>
                    <p class="mt-2 text-sm text-muted">{{ settings('company.address_london') }}</p>
                    <p class="mt-2 text-sm font-medium text-navy">{{ settings('company.phone_uk') }}</p>
                </div>
                <div class="rounded-2xl border border-line bg-canvas p-6">
                    <h3 class="font-display text-xl text-navy">Khulna Office</h3>
                    <p class="mt-2 text-sm text-muted">{{ settings('company.address_khulna') }}</p>
                    <p class="mt-2 text-sm font-medium text-navy">{{ settings('company.phone_bd_1') }}</p>
                    <p class="text-sm font-medium text-navy">{{ settings('company.phone_bd_2') }}</p>
                </div>
                <div class="rounded-2xl border border-line bg-navy p-6 text-white">
                    <h3 class="font-display text-xl">Email us</h3>
                    <a href="mailto:{{ settings('company.email') }}" class="mt-2 block text-sm text-orange hover:underline">{{ settings('company.email') }}</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
