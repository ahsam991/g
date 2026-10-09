<x-layouts.crm :title="$branch->exists ? 'Edit office' : 'Add office'">
    <x-page-header :title="$branch->exists ? $branch->name : 'Add office'" />

    <form method="POST"
          action="{{ $branch->exists ? route('admin.branches.update', $branch) : route('admin.branches.store') }}"
          class="max-w-2xl space-y-5 rounded-xl border border-line bg-white p-6 shadow-glass">
        @csrf
        @if($branch->exists) @method('PUT') @endif

        <div class="grid gap-5 sm:grid-cols-2">
            @foreach([
                'name' => 'Office name',
                'code' => 'Short code',
                'city' => 'City',
                'country' => 'Country',
                'phone' => 'Phone',
                'email' => 'Email',
            ] as $field => $label)
                <div>
                    <x-input-label :for="$field" :value="$label" />
                    <x-text-input :id="$field" :name="$field" class="mt-1 block w-full"
                                  :value="old($field, $branch->{$field})" />
                    <x-input-error :messages="$errors->get($field)" class="mt-2" />
                </div>
            @endforeach
        </div>

        <div>
            <x-input-label for="address" value="Address" />
            <x-text-input id="address" name="address" class="mt-1 block w-full" :value="old('address', $branch->address)" />
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $branch->exists ? $branch->is_active : true))
                   class="rounded border-line text-orange focus:ring-orange" />
            Active
        </label>

        <div class="flex items-center gap-3">
            <x-primary-button>{{ $branch->exists ? 'Save changes' : 'Create office' }}</x-primary-button>
            <a href="{{ route('admin.branches.index') }}" class="text-sm text-muted underline">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
