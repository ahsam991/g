<x-layouts.crm>
    <x-slot:title>{{ $lead->uid }}</x-slot:title>

    <div class="mb-4 flex flex-wrap items-center gap-3">
        <a href="{{ route('leads.index') }}" class="text-sm text-muted hover:text-navy">&larr; All leads</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Main info --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl border border-line bg-white p-6 shadow-glass">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h1 class="font-display text-2xl text-navy">{{ $lead->fullName() }}</h1>
                        <p class="text-sm text-muted">{{ $lead->uid }} &middot; {{ $lead->email }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <x-status-badge :status="$lead->status" />
                        @if($lead->is_duplicate)
                            <span class="rounded-full bg-warning/10 px-3 py-1 text-xs font-medium text-warning">Possible duplicate</span>
                        @endif
                    </div>
                </div>

                <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-xs font-medium text-muted">Phone</dt><dd class="mt-1 text-sm text-navy">{{ $lead->phone ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium text-muted">Country</dt><dd class="mt-1 text-sm text-navy">{{ $lead->country ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium text-muted">Destination</dt><dd class="mt-1 text-sm text-navy">{{ $lead->preferred_destination ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium text-muted">Level</dt><dd class="mt-1 text-sm text-navy">{{ $lead->preferred_level ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium text-muted">Subject</dt><dd class="mt-1 text-sm text-navy">{{ $lead->preferred_subject ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium text-muted">Source</dt><dd class="mt-1 text-sm text-navy">{{ $lead->source?->name ?? '-' }}</dd></div>
                    <div><dt class="text-xs font-medium text-muted">Consultant</dt><dd class="mt-1 text-sm text-navy">{{ $lead->consultant?->name ?? 'Unassigned' }}</dd></div>
                    <div><dt class="text-xs font-medium text-muted">Branch</dt><dd class="mt-1 text-sm text-navy">{{ $lead->branch?->name ?? '-' }}</dd></div>
                </dl>

                @if($lead->notes)
                    <div class="mt-6 rounded-xl bg-canvas p-4">
                        <p class="text-xs font-medium text-muted">Notes</p>
                        <p class="mt-1 text-sm text-navy whitespace-pre-line">{{ $lead->notes }}</p>
                    </div>
                @endif

                @if($lead->candidate)
                    <div class="mt-6 rounded-xl border border-success/30 bg-success/5 p-4">
                        <p class="text-sm font-medium text-success">Converted to student <a href="{{ route('candidates.show', $lead->candidate) }}" class="underline">{{ $lead->candidate->uid }}</a></p>
                    </div>
                @endif
            </div>

            {{-- Activity log --}}
            <div class="rounded-2xl border border-line bg-white p-6 shadow-glass">
                <h2 class="font-display text-lg text-navy">Activity</h2>

                @can('update', $lead)
                    <form method="POST" action="{{ route('leads.activity', $lead) }}" class="mt-4 flex flex-col gap-3 rounded-xl border border-line bg-canvas p-4 sm:flex-row">
                        @csrf
                        <select name="type" class="rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                            <option value="note">Note</option>
                            <option value="call">Call</option>
                            <option value="email">Email</option>
                        </select>
                        <input type="text" name="description" required placeholder="What happened?" class="flex-1 rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                        <button class="rounded-xl bg-navy px-4 py-2 text-sm font-semibold text-white hover:bg-navy-700">Log</button>
                    </form>
                @endcan

                <ol class="mt-4 space-y-3">
                    @forelse($activities as $activity)
                        <li class="flex gap-3 text-sm">
                            <span class="mt-0.5 grid h-7 w-7 shrink-0 place-items-center rounded-full
                                {{ $activity->type === 'conversion' ? 'bg-success/10 text-success' : ($activity->type === 'status_change' ? 'bg-orange/10 text-orange' : 'bg-navy/5 text-navy') }}">
                                @if($activity->type === 'conversion') ✓
                                @elseif($activity->type === 'call') 📞
                                @elseif($activity->type === 'email') ✉
                                @else 📝
                                @endif
                            </span>
                            <div>
                                <p class="text-navy">{{ $activity->description }}</p>
                                <p class="text-xs text-muted">{{ $activity->user?->name ?? 'System' }} &middot; {{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-muted">No activity yet.</li>
                    @endforelse
                </ol>
            </div>
        </div>

        {{-- Sidebar actions --}}
        <div class="space-y-4">
            @can('update', $lead)
                <a href="{{ route('leads.edit', $lead) }}" class="block w-full rounded-xl border border-line bg-white px-5 py-3 text-center text-sm font-semibold text-navy shadow-glass hover:bg-canvas">Edit lead</a>
            @endcan

            @can('convert', $lead)
                <form method="POST" action="{{ route('leads.convert', $lead) }}" onsubmit="return confirm('Convert this lead to a student? This cannot be undone.')">
                    @csrf
                    <button class="w-full rounded-xl bg-success px-5 py-3 text-sm font-semibold text-white hover:bg-success/90">Convert to student</button>
                </form>
            @endcan

            @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('leads.assign', $lead) }}" class="rounded-2xl border border-line bg-white p-4 shadow-glass">
                    @csrf
                    <label class="block text-xs font-medium text-muted">Reassign to</label>
                    <select name="assigned_to" class="mt-1 w-full rounded-xl border-line text-sm focus:border-orange focus:ring-orange">
                        @foreach(\App\Models\User::whereHas('role', fn($q) => $q->where('slug', 'consultant'))->where('status', 'active')->orderBy('name')->get() as $c)
                            <option value="{{ $c->id }}" @selected($lead->assigned_to === $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <button class="mt-2 w-full rounded-xl bg-navy px-4 py-2 text-sm font-semibold text-white hover:bg-navy-700">Reassign</button>
                </form>
            @endif

            <div class="rounded-2xl border border-line bg-white p-4 shadow-glass text-sm">
                <p class="text-xs font-medium text-muted">Timeline</p>
                <dl class="mt-2 space-y-2">
                    <div><dt class="text-muted">Created</dt><dd class="font-medium text-navy">{{ $lead->created_at->format('d M Y H:i') }}</dd></div>
                    @if($lead->contacted_at)
                        <div><dt class="text-muted">First contact</dt><dd class="font-medium text-navy">{{ $lead->contacted_at->format('d M Y H:i') }}</dd></div>
                    @endif
                    @if($lead->converted_at)
                        <div><dt class="text-muted">Converted</dt><dd class="font-medium text-success">{{ $lead->converted_at->format('d M Y H:i') }}</dd></div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</x-layouts.crm>
