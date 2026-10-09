<x-layouts.crm title="Students">
    <x-page-header title="Students" :subtitle="$candidates->total().' '.Str::plural('record', $candidates->total())">
        <x-slot:actions>
            @can('import', App\Models\Candidate::class)
                <a href="{{ route('candidates.export', request()->query()) }}"
                   class="rounded-xl border border-line px-4 py-2 text-sm font-medium">Export</a>
                <a href="{{ route('candidates.import') }}"
                   class="rounded-xl border border-line px-4 py-2 text-sm font-medium">Import</a>
            @endcan
            @can('create', App\Models\Candidate::class)
                <a href="{{ route('candidates.create') }}"
                   class="rounded-xl bg-ink px-4 py-2 text-sm font-medium text-white transition-transform active:scale-[.98]">
                    Add student
                </a>
            @endcan
        </x-slot:actions>
    </x-page-header>

    <form method="POST" action="{{ route('candidates.bulk') }}" x-data="{ selected: [] }">
        @csrf

        <x-data-table :rows="$candidates" placeholder="Reference, name, email, phone or passport">
            <x-slot:filters>
                <select name="status" class="rounded-xl border-line text-sm">
                    <option value="">Any status</option>
                    @foreach(['new', 'in_progress', 'applied', 'enrolled', 'lost'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ Str::headline($status) }}</option>
                    @endforeach
                </select>

                @can('assign', App\Models\Candidate::class)
                    <select name="assigned_to" class="rounded-xl border-line text-sm">
                        <option value="">Any consultant</option>
                        @foreach($consultants as $consultant)
                            <option value="{{ $consultant->id }}" @selected(request('assigned_to') == $consultant->id)>{{ $consultant->name }}</option>
                        @endforeach
                    </select>
                @endcan

                <label class="flex items-center gap-2 text-sm text-muted">
                    <input type="checkbox" name="archived" value="1" @checked(request()->boolean('archived'))
                           class="rounded border-line text-orange focus:ring-orange" />
                    Archived
                </label>
            </x-slot:filters>

            <x-slot:head>
                <th class="w-10 px-4 py-3"></th>
                <th class="px-4 py-3">Student</th>
                <th class="px-4 py-3">Destination</th>
                <th class="px-4 py-3">Consultant</th>
                <th class="px-4 py-3">Profile</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3"></th>
            </x-slot:head>

            <x-slot:body>
                @forelse($candidates as $candidate)
                    <tr class="hover:bg-canvas">
                        <td class="px-4 py-3">
                            <input type="checkbox" name="ids[]" value="{{ $candidate->id }}" x-model="selected"
                                   class="rounded border-line text-orange focus:ring-orange" />
                        </td>
                        <td class="px-4 py-3">
                            <a href="{{ route('candidates.show', $candidate) }}" class="font-medium text-navy hover:underline">
                                {{ $candidate->full_name }}
                            </a>
                            <p class="text-xs text-muted">{{ $candidate->uid }} · {{ $candidate->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p>{{ $candidate->preferred_destination ?? '—' }}</p>
                            <p class="text-xs text-muted">{{ $candidate->preferred_intake }}</p>
                        </td>
                        <td class="px-4 py-3">{{ $candidate->consultant?->name ?? 'Unassigned' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 w-16 overflow-hidden rounded-full bg-line">
                                    <div class="h-full rounded-full bg-orange" style="width: {{ $candidate->completion_percent }}%"></div>
                                </div>
                                <span class="text-xs text-muted">{{ $candidate->completion_percent }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3"><x-status-badge :status="$candidate->status" /></td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('candidates.show', $candidate) }}" class="text-sm font-medium text-navy underline">Open</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-0">
                            <x-empty-state title="No students match"
                                           message="Adjust the filters, or add the first student." />
                        </td>
                    </tr>
                @endforelse
            </x-slot:body>
        </x-data-table>

        {{-- Bulk bar, only once something is ticked --}}
        <div x-show="selected.length" x-cloak
             class="sticky bottom-4 mt-4 flex flex-wrap items-center gap-3 rounded-xl border border-line bg-white/80 p-4 shadow-glass backdrop-blur-xl">
            <span class="text-sm font-medium" x-text="selected.length + ' selected'"></span>

            @can('assign', App\Models\Candidate::class)
                <select name="assigned_to" class="rounded-xl border-line text-sm">
                    <option value="">Choose consultant</option>
                    @foreach($consultants as $consultant)
                        <option value="{{ $consultant->id }}">{{ $consultant->name }}</option>
                    @endforeach
                </select>
                <button name="action" value="assign" class="rounded-xl bg-navy px-3 py-2 text-sm text-white">Assign</button>
            @endcan

            <button name="action" value="archive" class="rounded-xl bg-ink px-3 py-2 text-sm text-white">Archive</button>
            <button name="action" value="restore" class="rounded-xl border border-line px-3 py-2 text-sm">Restore</button>
        </div>
    </form>
</x-layouts.crm>
