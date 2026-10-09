<x-layouts.crm title="Offices">
    <x-page-header title="Offices" subtitle="Branches students and consultants belong to.">
        <x-slot:actions>
            <a href="{{ route('admin.branches.create') }}"
               class="rounded-xl bg-ink px-4 py-2 text-sm font-medium text-white transition-transform active:scale-[.98]">
                Add office
            </a>
        </x-slot:actions>
    </x-page-header>

    <x-data-table :rows="$branches" :searchable="false">
        <x-slot:head>
            <th class="px-4 py-3">Office</th>
            <th class="px-4 py-3">Location</th>
            <th class="px-4 py-3">Contact</th>
            <th class="px-4 py-3">Staff</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3"></th>
        </x-slot:head>

        <x-slot:body>
            @foreach($branches as $branch)
                <tr class="hover:bg-canvas">
                    <td class="px-4 py-3">
                        <p class="font-medium">{{ $branch->name }}</p>
                        <p class="text-xs text-muted">{{ $branch->code }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p>{{ $branch->city }}</p>
                        <p class="text-xs text-muted">{{ $branch->country }}</p>
                    </td>
                    <td class="px-4 py-3 text-muted">{{ $branch->phone }}</td>
                    <td class="px-4 py-3">{{ $branch->users_count }}</td>
                    <td class="px-4 py-3">
                        <x-status-badge :status="$branch->is_active ? 'active' : 'inactive'" />
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.branches.edit', $branch) }}" class="text-sm font-medium text-navy underline">Edit</a>
                    </td>
                </tr>
            @endforeach
        </x-slot:body>
    </x-data-table>
</x-layouts.crm>
