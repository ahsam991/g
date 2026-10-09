<x-layouts.crm :title="$candidate->full_name">
    <x-page-header :title="$candidate->full_name" :subtitle="$candidate->uid.' · added '.$candidate->created_at->format('d M Y')">
        <x-slot:actions>
            @can('update', $candidate)
                <a href="{{ route('candidates.edit', $candidate) }}"
                   class="rounded-xl bg-ink px-4 py-2 text-sm font-medium text-white">Edit</a>
            @endcan
            @can('archive', $candidate)
                <form method="POST" action="{{ route($candidate->isArchived() ? 'candidates.restore' : 'candidates.archive', $candidate) }}">
                    @csrf
                    <button class="rounded-xl border border-line px-4 py-2 text-sm">
                        {{ $candidate->isArchived() ? 'Restore' : 'Archive' }}
                    </button>
                </form>
            @endcan
        </x-slot:actions>
    </x-page-header>

    @if($candidate->isArchived())
        <div class="mb-4 rounded-xl border border-warning/30 bg-warning/10 px-4 py-3 text-sm">
            This student is archived. Nothing new can be added until they are restored.
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <section class="rounded-xl border border-line bg-white p-6 shadow-glass">
                <h3 class="font-display text-xl text-navy">Personal</h3>

                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach([
                        'Email' => $candidate->email,
                        'Phone' => $candidate->phone,
                        'Date of birth' => $candidate->date_of_birth?->format('d M Y'),
                        'Nationality' => $candidate->nationality,
                        'Passport' => $candidate->passport_number,
                        'Passport expiry' => $candidate->passport_expiry?->format('d M Y'),
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-muted">{{ $label }}</dt>
                            <dd class="mt-0.5">{{ $value ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>

            <section class="rounded-xl border border-line bg-white p-6 shadow-glass">
                <h3 class="font-display text-xl text-navy">Study plan</h3>

                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    @foreach([
                        'Destination' => $candidate->preferred_destination,
                        'Level' => $candidate->preferred_level,
                        'Subject' => $candidate->preferred_subject,
                        'Intake' => $candidate->preferred_intake,
                        'Budget' => $candidate->budget_per_year ? $candidate->budget_currency.' '.number_format((float) $candidate->budget_per_year) : null,
                        'Source' => $candidate->source,
                    ] as $label => $value)
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-muted">{{ $label }}</dt>
                            <dd class="mt-0.5">{{ $value ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>

            <section class="rounded-xl border border-line bg-white p-6 shadow-glass">
                <h3 class="font-display text-xl text-navy">Qualifications</h3>

                @if($candidate->qualifications->isEmpty())
                    <p class="mt-3 text-sm text-muted">None recorded yet.</p>
                @else
                    <ul class="mt-4 divide-y divide-line">
                        @foreach($candidate->qualifications as $qualification)
                            <li class="py-3">
                                <p class="font-medium">{{ $qualification->level }} · {{ $qualification->subject }}</p>
                                <p class="text-sm text-muted">
                                    {{ $qualification->institution }}
                                    @if($qualification->end_year) · {{ $qualification->end_year }} @endif
                                    @if($qualification->grade) · {{ $qualification->grade }} @endif
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="rounded-xl border border-line bg-white p-6 shadow-glass">
                <h3 class="font-display text-xl text-navy">English tests</h3>

                @if($candidate->englishTests->isEmpty())
                    <p class="mt-3 text-sm text-muted">None recorded yet.</p>
                @else
                    <ul class="mt-4 divide-y divide-line">
                        @foreach($candidate->englishTests as $test)
                            <li class="flex items-center justify-between py-3">
                                <div>
                                    <p class="font-medium">{{ $test->type }}</p>
                                    <p class="text-sm text-muted">{{ $test->test_date?->format('d M Y') }}</p>
                                </div>
                                <p class="font-display text-2xl text-navy">{{ $test->overall }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-xl border border-line bg-white p-6 shadow-glass">
                <h3 class="font-display text-xl text-navy">Profile completeness</h3>

                <p class="mt-2 font-display text-4xl text-orange">{{ $candidate->completion_percent }}%</p>

                <ul class="mt-4 space-y-2 text-sm">
                    @foreach($breakdown as $section => $points)
                        <li class="flex items-center justify-between">
                            <span class="capitalize text-muted">{{ $section }}</span>
                            <span>{{ round($points) }} / {{ App\Models\Candidate::COMPLETION_WEIGHTS[$section] }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="rounded-xl border border-line bg-white p-6 shadow-glass">
                <h3 class="font-display text-xl text-navy">Ownership</h3>

                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-muted">Consultant</dt>
                        <dd>{{ $candidate->consultant?->name ?? 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-muted">Office</dt>
                        <dd>{{ $candidate->branch?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wide text-muted">Portal account</dt>
                        <dd>{{ $candidate->user_id ? 'Active' : 'Not registered' }}</dd>
                    </div>
                </dl>
            </section>

            @if($candidate->notes)
                <section class="rounded-xl border border-line bg-white p-6 shadow-glass">
                    <h3 class="font-display text-xl text-navy">Notes</h3>
                    <p class="mt-3 whitespace-pre-line text-sm">{{ $candidate->notes }}</p>
                </section>
            @endif
        </aside>
    </div>
</x-layouts.crm>
