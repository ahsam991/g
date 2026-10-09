<x-layouts.crm title="Settings">
    <x-page-header title="Settings" subtitle="Values used across the website, CRM and portal." />

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        @foreach($groups as $group => $settings)
            <fieldset class="rounded-xl border border-line bg-white p-5 shadow-glass">
                <legend class="px-2 font-display text-lg capitalize text-navy">{{ $group }}</legend>

                <div class="grid gap-4 sm:grid-cols-2">
                    @foreach($settings as $setting)
                        <div>
                            <x-input-label :for="$setting->key" :value="$setting->label ?? $setting->key" />
                            <x-text-input :id="$setting->key" :name="'settings['.$setting->key.']'"
                                          class="mt-1 block w-full" :value="old('settings.'.$setting->key, $setting->value)" />
                            <p class="mt-1 text-xs text-muted">{{ $setting->key }}</p>
                        </div>
                    @endforeach
                </div>
            </fieldset>
        @endforeach

        <x-primary-button>Save settings</x-primary-button>
    </form>
</x-layouts.crm>
