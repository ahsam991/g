<x-layouts.crm title="Audit log">
    <x-page-header title="Audit log" subtitle="Every change, who made it and when. Secrets are never recorded." />

    <x-data-table :rows="$logs" :searchable="false">
        <x-slot:head>
            <th class="px-4 py-3">When</th>
            <th class="px-4 py-3">Who</th>
            <th class="px-4 py-3">Action</th>
            <th class="px-4 py-3">Record</th>
            <th class="px-4 py-3">Changed</th>
        </x-slot:head>

        <x-slot:body>
            @forelse($logs as $log)
                <tr class="align-top hover:bg-canvas">
                    <td class="whitespace-nowrap px-4 py-3 text-muted">{{ $log->created_at?->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3">{{ $log->user?->name ?? 'System' }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$log->action" /></td>
                    <td class="px-4 py-3">
                        <p>{{ class_basename($log->auditable_type) }}</p>
                        <p class="text-xs text-muted">#{{ $log->auditable_id }}</p>
                    </td>
                    <td class="px-4 py-3 text-xs text-muted">
                        {{ Str::limit(implode(', ', array_keys($log->after ?? [])), 90) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-0"><x-empty-state title="Nothing logged yet" /></td>
                </tr>
            @endforelse
        </x-slot:body>
    </x-data-table>
</x-layouts.crm>
