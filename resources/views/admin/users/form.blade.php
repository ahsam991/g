<x-layouts.crm :title="$user->exists ? 'Edit user' : 'Add user'">
    <x-page-header :title="$user->exists ? $user->name : 'Add user'" subtitle="CRM sign-in details and role." />

    <form method="POST"
          action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}"
          class="max-w-2xl space-y-5 rounded-xl border border-line bg-white p-6 shadow-glass">
        @csrf
        @if($user->exists) @method('PUT') @endif

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="name" value="Full name" />
                <x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="phone" value="Phone" />
                <x-text-input id="phone" name="phone" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
            </div>

            <div>
                <x-input-label for="role_id" value="Role" />
                <select id="role_id" name="role_id" class="mt-1 block w-full rounded-xl border-line" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="branch_id" value="Office" />
                <select id="branch_id" name="branch_id" class="mt-1 block w-full rounded-xl border-line">
                    <option value="">Not set</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" @selected(old('branch_id', $user->branch_id) == $branch->id)>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="status" value="Status" />
                <select id="status" name="status" class="mt-1 block w-full rounded-xl border-line" required>
                    <option value="active" @selected(old('status', $user->status) === 'active')>Active</option>
                    <option value="suspended" @selected(old('status', $user->status) === 'suspended')>Suspended</option>
                </select>
            </div>
        </div>

        <div class="grid gap-5 border-t border-line pt-5 sm:grid-cols-2">
            <div>
                <x-input-label for="password" :value="$user->exists ? 'New password (optional)' : 'Password'" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <p class="mt-1 text-xs text-muted">At least 12 characters, mixed case, with a number.</p>
            </div>

            <div>
                <x-input-label for="password_confirmation" value="Confirm password" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                              class="mt-1 block w-full" autocomplete="new-password" />
            </div>
        </div>

        <div class="flex items-center gap-3">
            <x-primary-button>{{ $user->exists ? 'Save changes' : 'Create user' }}</x-primary-button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-muted underline">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
