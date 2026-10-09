<x-layouts.crm title="Users">
    <x-page-header title="Users" subtitle="Everyone who can sign in to the CRM.">
        <x-slot:actions>
            <a href="{{ route('admin.users.create') }}"
               class="rounded-xl bg-ink px-4 py-2 text-sm font-medium text-white transition-transform active:scale-[.98]">
                Add user
            </a>
        </x-slot:actions>
    </x-page-header>

    <x-data-table :rows="$users" placeholder="Search name or email">
        <x-slot:filters>
            <select name="role" class="rounded-xl border-line text-sm">
                <option value="">All roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->slug }}" @selected(request('role') === $role->slug)>{{ $role->name }}</option>
                @endforeach
            </select>
        </x-slot:filters>

        <x-slot:head>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">Role</th>
            <th class="px-4 py-3">Office</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Last sign-in</th>
            <th class="px-4 py-3"></th>
        </x-slot:head>

        <x-slot:body>
            @forelse($users as $user)
                <tr class="hover:bg-canvas">
                    <td class="px-4 py-3">
                        <p class="font-medium">{{ $user->name }}</p>
                        <p class="text-xs text-muted">{{ $user->email }}</p>
                    </td>
                    <td class="px-4 py-3">{{ $user->role?->name ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $user->branch?->name ?? '—' }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$user->status" /></td>
                    <td class="px-4 py-3 text-muted">{{ $user->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-medium text-navy underline">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="p-0">
                        <x-empty-state title="No users found" message="Try a different search or add a user." />
                    </td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>
</x-layouts.crm>
