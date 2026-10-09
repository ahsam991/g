<x-layouts.crm title="Edit permissions">
    <x-page-header :title="$role->name" subtitle="Tick what this role may do." />

    <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <x-input-error :messages="$errors->get('permissions')" />

        @foreach($catalogue as $group => $permissions)
            <fieldset class="rounded-xl border border-line bg-white p-5 shadow-glass">
                <legend class="px-2 text-sm font-medium text-navy">{{ $group }}</legend>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($permissions as $slug => $label)
                        <label class="flex items-start gap-2 text-sm">
                            <input type="checkbox" name="permissions[]" value="{{ $slug }}"
                                   @checked(in_array($slug, old('permissions', $held), true))
                                   class="mt-0.5 rounded border-line text-orange focus:ring-orange" />
                            <span>
                                {{ $label }}
                                <span class="block text-xs text-muted">{{ $slug }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @endforeach

        <div class="flex items-center gap-3">
            <x-primary-button>Save permissions</x-primary-button>
            <a href="{{ route('admin.roles.index') }}" class="text-sm text-muted underline">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
