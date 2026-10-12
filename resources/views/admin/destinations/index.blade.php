<x-layouts.crm title="Destinations">
    <x-page-header title="Destinations" description="Manage study abroad destinations shown on the public site.">
        <a href="{{ route('admin.destinations.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-orange px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-orange/90">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Destination
        </a>
    </x-page-header>

    @if(session('status'))
        <div class="mb-4 rounded-lg bg-success/10 px-4 py-3 text-sm text-success">{{ session('status') }}</div>
    @endif

    @if($destinations->isEmpty())
        <x-empty-state title="No destinations" description="Add your first study abroad destination." />
    @else
        <div class="overflow-hidden rounded-xl border border-line bg-white shadow-sm">
            <table class="min-w-full divide-y divide-line text-sm">
                <thead class="bg-canvas">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-muted">Flag</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Universities</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Status</th>
                        <th class="px-4 py-3 text-right font-medium text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach($destinations as $dest)
                        <tr class="hover:bg-canvas/50">
                            <td class="px-4 py-3 text-2xl">{{ $dest->flag }}</td>
                            <td class="px-4 py-3 font-medium text-ink">{{ $dest->name }}</td>
                            <td class="px-4 py-3 text-muted">{{ $dest->universities_count }}</td>
                            <td class="px-4 py-3">
                                @if($dest->active)
                                    <span class="inline-flex rounded-full bg-success/10 px-2.5 py-0.5 text-xs font-medium text-success">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-muted/10 px-2.5 py-0.5 text-xs font-medium text-muted">Inactive</span>
                                @endif
                                @if($dest->featured)
                                    <span class="ml-1 inline-flex rounded-full bg-orange/10 px-2.5 py-0.5 text-xs font-medium text-orange">Featured</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.destinations.edit', $dest) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-navy hover:bg-navy/5 transition">Edit</a>
                                    <form method="POST" action="{{ route('admin.destinations.destroy', $dest) }}" onsubmit="return confirm('Delete this destination and all its universities?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.crm>
