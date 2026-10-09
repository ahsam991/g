<x-layouts.crm>
    <x-slot:title>Leads</x-slot:title>

    <x-page-header title="Leads">
        @can('create', App\Models\Lead::class)
            <a href="{{ route('leads.create') }}" class="rounded-xl bg-navy px-5 py-2.5 text-sm font-semibold text-white hover:bg-navy-700">New lead</a>
        @endcan
    </x-page-header>

    {{-- Filters --}}
    <form method="GET" class="mb-6 grid gap-3 rounded-2xl border border-line bg-white p-4 shadow-glass sm:grid-cols-2 lg:grid-cols-5">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, phone…" class="rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
        <select name="status" class="rounded-xl border-line text-sm focus:border-orange focus:ring-orange" onchange="this.form.submit()">
            <option value="">All statuses</option>
            @foreach(App\Models\Lead::STATUSES as $s)
                <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="source" class="rounded-xl border-line text-sm focus:border-orange focus:ring-orange" onchange="this.form.submit()">
            <option value="">All sources</option>
            @foreach($sources as $src)
                <option value="{{ $src->id }}" @selected(request('source') == $src->id)>{{ $src->name }}</option>
            @endforeach
        </select>
        @if(auth()->user()->isAdmin())
            <select name="consultant" class="rounded-xl border-line text-sm focus:border-orange focus:ring-orange" onchange="this.form.submit()">
                <option value="">All consultants</option>
                @foreach($consultants as $c)
                    <option value="{{ $c->id }}" @selected(request('consultant') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        @endif
        <button class="rounded-xl bg-navy px-4 py-2 text-sm font-semibold text-white">Filter</button>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-2xl border border-line bg-white shadow-glass">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-line bg-canvas text-xs font-semibold uppercase text-muted">
                <tr>
                    <th class="px-4 py-3">UID</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Source</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Consultant</th>
                    <th class="px-4 py-3">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @forelse($leads as $lead)
                    <tr class="transition-colors hover:bg-canvas/50">
                        <td class="px-4 py-3">
                            <a href="{{ route('leads.show', $lead) }}" class="font-medium text-navy hover:text-orange">{{ $lead->uid }}</a>
                        </td>
                        <td class="px-4 py-3">
                            {{ $lead->fullName() }}
                            @if($lead->is_duplicate)
                                <span class="ml-1 inline-flex rounded-full bg-warning/10 px-2 py-0.5 text-xs font-medium text-warning">Duplicate</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-muted">{{ $lead->email }}</td>
                        <td class="px-4 py-3 text-muted">{{ $lead->source?->name ?? '-' }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$lead->status" /></td>
                        <td class="px-4 py-3 text-muted">{{ $lead->consultant?->name ?? 'Unassigned' }}</td>
                        <td class="px-4 py-3 text-muted">{{ $lead->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-muted">No leads found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $leads->links() }}</div>
</x-layouts.crm>
