<x-layouts.crm title="Universities">
    <x-page-header title="Universities" description="Manage partner universities and their information.">
        <a href="{{ route('admin.universities.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-orange px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-orange/90">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add University
        </a>
    </x-page-header>

    @if(session('status'))
        <div class="mb-4 rounded-lg bg-success/10 px-4 py-3 text-sm text-success">{{ session('status') }}</div>
    @endif

    <form class="mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search universities..."
               class="rounded-lg border border-line bg-canvas px-4 py-2 text-sm shadow-sm focus:border-navy focus:ring-navy">
        <select name="destination" class="rounded-lg border border-line bg-canvas px-4 py-2 text-sm shadow-sm focus:border-navy focus:ring-navy" onchange="this.form.submit()">
            <option value="">All Destinations</option>
            @foreach($destinations as $dest)
                <option value="{{ $dest->id }}" {{ request('destination') == $dest->id ? 'selected' : '' }}>{{ $dest->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-lg bg-navy px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-navy-700">Filter</button>
    </form>

    @if($universities->isEmpty())
        <x-empty-state title="No universities" description="Add your first partner university." />
    @else
        <div class="overflow-hidden rounded-xl border border-line bg-white shadow-sm">
            <table class="min-w-full divide-y divide-line text-sm">
                <thead class="bg-canvas">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-muted">University</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Destination</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">City</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Courses</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Status</th>
                        <th class="px-4 py-3 text-right font-medium text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach($universities as $uni)
                        <tr class="hover:bg-canvas/50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if($uni->logo)
                                        <img src="{{ asset('storage/' . $uni->logo) }}" alt="" class="h-8 w-8 rounded-lg object-contain">
                                    @else
                                        <div class="grid h-8 w-8 place-items-center rounded-lg bg-navy/5 text-xs font-bold text-navy">{{ Str::substr($uni->name, 0, 2) }}</div>
                                    @endif
                                    <span class="font-medium text-ink">{{ $uni->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $uni->destination->name }}</td>
                            <td class="px-4 py-3 text-muted">{{ $uni->city ?? '—' }}</td>
                            <td class="px-4 py-3 text-muted">{{ $uni->courses_count }}</td>
                            <td class="px-4 py-3">
                                @if($uni->partner)
                                    <span class="inline-flex rounded-full bg-navy/10 px-2.5 py-0.5 text-xs font-medium text-navy">Partner</span>
                                @endif
                                @if(!$uni->active)
                                    <span class="inline-flex rounded-full bg-muted/10 px-2.5 py-0.5 text-xs font-medium text-muted">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.universities.edit', $uni) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-navy hover:bg-navy/5 transition">Edit</a>
                                    <form method="POST" action="{{ route('admin.universities.destroy', $uni) }}" onsubmit="return confirm('Delete this university?')">
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
        <div class="mt-4">{{ $universities->links() }}</div>
    @endif
</x-layouts.crm>
