<x-layouts.crm title="Access Log">
    <x-page-header :title="'Access Log — '.$document->file_name" :subtitle="$document->documentType->name">
        <x-slot:actions>
            <a href="{{ route('admin.documents.candidate', $document->candidate) }}" class="rounded-xl border border-line px-4 py-2 text-sm font-medium text-ink hover:bg-canvas">Back</a>
        </x-slot:actions>
    </x-page-header>

    <div class="overflow-hidden rounded-2xl border border-line bg-white">
        <table class="min-w-full text-sm">
            <thead class="border-b border-line bg-canvas/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-muted">User</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">Action</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">IP</th>
                    <th class="px-4 py-3 text-left font-medium text-muted">When</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse($logs as $log)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $log->user->name }}</td>
                        <td class="px-4 py-3">
                            <span @class([
                                'rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-blue-50 text-blue-700' => $log->action === 'view',
                                'bg-green-50 text-green-700' => $log->action === 'download',
                            ])>{{ ucfirst($log->action) }}</span>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-muted">{{ $log->ip }}</td>
                        <td class="px-4 py-3 text-muted">{{ $log->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-muted">No access logs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
</x-layouts.crm>
