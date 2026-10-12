<x-layouts.crm title="Documents">
    <x-page-header title="Documents" subtitle="All uploaded documents across students">
        <x-slot:actions>
            <a href="{{ route('candidates.index') }}" class="rounded-xl bg-navy px-4 py-2 text-sm font-semibold text-white hover:bg-navy/90">Upload for student</a>
        </x-slot:actions>
    </x-page-header>

    {{-- Filters --}}
    <form method="GET" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-xs font-medium text-muted">Status</label>
            <select name="status" class="mt-1 rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                <option value="">All statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-muted">Type</label>
            <select name="type" class="mt-1 rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                <option value="">All types</option>
                @foreach($types as $t)
                    <option value="{{ $t->id }}" @selected(request('type') == $t->id)>{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-muted">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, UID, file..."
                   class="mt-1 rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
        </div>
        <button type="submit" class="rounded-xl bg-canvas px-4 py-2 text-sm font-medium text-ink hover:bg-line">Filter</button>
        @if(request()->hasAny(['status', 'type', 'search', 'candidate']))
            <a href="{{ route('admin.documents.index') }}" class="text-sm text-orange hover:underline">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-line bg-white">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="border-b border-line bg-canvas/50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-muted">Student</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Document</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Type</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Status</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Uploaded</th>
                        <th class="px-4 py-3 text-right font-medium text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-canvas/30">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.documents.candidate', $doc->candidate) }}" class="font-medium text-navy hover:text-orange">
                                    {{ $doc->candidate->first_name }} {{ $doc->candidate->last_name }}
                                </a>
                                <p class="text-xs text-muted">{{ $doc->candidate->uid }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="max-w-[200px] truncate font-medium">{{ $doc->file_name }}</p>
                                <p class="text-xs text-muted">v{{ $doc->version }} · {{ number_format($doc->file_size / 1024, 0) }} KB</p>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $doc->documentType->name }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    'bg-amber-50 text-amber-700' => $doc->status === 'pending',
                                    'bg-blue-50 text-blue-700' => $doc->status === 'under_review',
                                    'bg-green-50 text-green-700' => $doc->status === 'verified',
                                    'bg-red-50 text-red-700' => $doc->status === 'rejected',
                                    'bg-red-100 text-red-800' => $doc->status === 'expired',
                                ])>{{ ucfirst(str_replace('_', ' ', $doc->status)) }}</span>
                                @if($doc->expiry_date && $doc->isExpiringSoon())
                                    <span class="ml-1 text-xs text-amber-600">Expires {{ $doc->expiry_date->diffForHumans() }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-muted">
                                {{ $doc->created_at->diffForHumans() }}
                                @if($doc->creator)
                                    <p class="text-xs">by {{ $doc->creator->name }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    @if(in_array($doc->mime_type, ['application/pdf', 'image/jpeg', 'image/png']))
                                        <a href="{{ route('admin.documents.preview', $doc) }}" target="_blank" class="text-muted hover:text-navy" title="Preview">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.documents.download', $doc) }}" class="text-muted hover:text-navy" title="Download">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    </a>
                                    @can('documents.verify')
                                        @if($doc->status === 'pending' || $doc->status === 'under_review')
                                            <form method="POST" action="{{ route('admin.documents.verify', $doc) }}">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800" title="Verify">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center text-muted">No documents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $documents->links() }}</div>
</x-layouts.crm>
