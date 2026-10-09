<x-layouts.crm title="Roles">
    <x-page-header title="Roles" subtitle="What each role is allowed to do." />

    <div class="grid gap-4 sm:grid-cols-3">
        @foreach($roles as $role)
            <div class="rounded-xl border border-line bg-white p-5 shadow-glass">
                <div class="flex items-start justify-between">
                    <h3 class="font-display text-xl text-navy">{{ $role->name }}</h3>
                    <span class="text-xs text-muted">{{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</span>
                </div>

                <p class="mt-2 text-sm text-muted">{{ $role->description }}</p>

                <p class="mt-4 text-sm">
                    <span class="font-medium">{{ $role->permissions->count() }}</span>
                    <span class="text-muted">of {{ count(\App\Support\Permissions::all()) }} permissions</span>
                </p>

                @if($role->slug !== \App\Models\Role::ADMIN)
                    <a href="{{ route('admin.roles.edit', $role) }}" class="mt-4 inline-block text-sm font-medium text-navy underline">
                        Edit permissions
                    </a>
                @else
                    <p class="mt-4 text-xs text-muted">Always holds every permission.</p>
                @endif
            </div>
        @endforeach
    </div>
</x-layouts.crm>
