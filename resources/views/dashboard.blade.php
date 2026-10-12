<x-layouts.crm title="Dashboard">
    <x-page-header :title="'Hello, '.Str::before(auth()->user()->name, ' ')"
                   :subtitle="auth()->user()->role?->name.' · '.(auth()->user()->branch?->name ?? 'No office set')" />

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Students" :value="$studentCount" />
        <x-stat-card label="Open leads" :value="$leadCount" tone="orange" />
        <x-stat-card label="New enquiries" :value="$enquiryCount" tone="warning" />
        <x-stat-card label="Universities" :value="$universityCount" />
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-2">
        <div class="rounded-xl border border-line bg-white p-4">
            <p class="text-xs font-medium uppercase tracking-wider text-muted">Active destinations</p>
            <p class="mt-1 text-2xl font-semibold text-navy">{{ $destinationCount }}</p>
        </div>
        <div class="rounded-xl border border-line bg-white p-4">
            <p class="text-xs font-medium uppercase tracking-wider text-muted">Partner universities</p>
            <p class="mt-1 text-2xl font-semibold text-navy">{{ $universityCount }}</p>
        </div>
    </div>

    {{-- Recent activity --}}
    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        {{-- Recent leads --}}
        <div class="rounded-2xl border border-line bg-white">
            <div class="flex items-center justify-between border-b border-line px-5 py-4">
                <h3 class="font-display text-lg text-navy">Recent Leads</h3>
                @can('leads.view')
                    <a href="{{ route('leads.index') }}" class="text-xs font-medium text-orange hover:underline">View all</a>
                @endcan
            </div>
            <div class="divide-y divide-line">
                @forelse($recentLeads as $lead)
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-navy/5 text-xs font-bold text-navy">
                            {{ Str::upper(Str::substr($lead->first_name, 0, 1)) }}{{ Str::upper(Str::substr($lead->last_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-ink">{{ $lead->first_name }} {{ $lead->last_name }}</p>
                            <p class="text-xs text-muted">{{ $lead->source?->name ?? 'Direct' }} · {{ $lead->created_at->diffForHumans() }}</p>
                        </div>
                        <span @class([
                            'rounded-full px-2 py-0.5 text-xs font-medium',
                            'bg-yellow-50 text-yellow-700' => $lead->status === 'new',
                            'bg-blue-50 text-blue-700' => $lead->status === 'contacted',
                            'bg-green-50 text-green-700' => $lead->status === 'qualified',
                            'bg-gray-100 text-gray-600' => !in_array($lead->status, ['new', 'contacted', 'qualified']),
                        ])>{{ ucfirst($lead->status) }}</span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-muted">No leads yet. They'll appear here as they come in.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent students --}}
        <div class="rounded-2xl border border-line bg-white">
            <div class="flex items-center justify-between border-b border-line px-5 py-4">
                <h3 class="font-display text-lg text-navy">Recent Students</h3>
                @can('candidates.view')
                    <a href="{{ route('candidates.index') }}" class="text-xs font-medium text-orange hover:underline">View all</a>
                @endcan
            </div>
            <div class="divide-y divide-line">
                @forelse($recentStudents as $student)
                    <div class="flex items-center gap-3 px-5 py-3">
                        <div class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-orange/10 text-xs font-bold text-orange">
                            {{ Str::upper(Str::substr($student->first_name, 0, 1)) }}{{ Str::upper(Str::substr($student->last_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-ink">{{ $student->first_name }} {{ $student->last_name }}</p>
                            <p class="text-xs text-muted">{{ $student->uid }} · {{ $student->created_at->diffForHumans() }}</p>
                        </div>
                        <span @class([
                            'rounded-full px-2 py-0.5 text-xs font-medium',
                            'bg-green-50 text-green-700' => $student->status === 'active',
                            'bg-gray-100 text-gray-600' => $student->status !== 'active',
                        ])>{{ ucfirst($student->status) }}</span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-sm text-muted">No students yet. Convert a lead or add one manually.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Quick actions --}}
    <div class="mt-8">
        <h3 class="mb-3 font-display text-lg text-navy">Quick Actions</h3>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @can('leads.create')
            <a href="{{ route('leads.create') }}" class="flex items-center gap-3 rounded-xl border border-line bg-white p-4 transition-all hover:-translate-y-0.5 hover:shadow-md hover:border-orange/30">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-orange/10 text-orange">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                </div>
                <span class="text-sm font-medium text-ink">Add new lead</span>
            </a>
            @endcan
            @can('candidates.create')
            <a href="{{ route('candidates.create') }}" class="flex items-center gap-3 rounded-xl border border-line bg-white p-4 transition-all hover:-translate-y-0.5 hover:shadow-md hover:border-orange/30">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-navy/5 text-navy">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                </div>
                <span class="text-sm font-medium text-ink">Add student</span>
            </a>
            @endcan
            @can('catalogue.manage')
            <a href="{{ route('admin.destinations.index') }}" class="flex items-center gap-3 rounded-xl border border-line bg-white p-4 transition-all hover:-translate-y-0.5 hover:shadow-md hover:border-orange/30">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5a17.92 17.92 0 0 1-8.716-2.247m0 0A8.966 8.966 0 0 1 3 12c0-1.97.633-3.794 1.707-5.277"/></svg>
                </div>
                <span class="text-sm font-medium text-ink">Manage destinations</span>
            </a>
            @endcan
            @can('settings.manage')
            <a href="{{ route('admin.settings.edit') }}" class="flex items-center gap-3 rounded-xl border border-line bg-white p-4 transition-all hover:-translate-y-0.5 hover:shadow-md hover:border-orange/30">
                <div class="grid h-10 w-10 place-items-center rounded-lg bg-gray-100 text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                </div>
                <span class="text-sm font-medium text-ink">Settings</span>
            </a>
            @endcan
        </div>
    </div>
</x-layouts.crm>
