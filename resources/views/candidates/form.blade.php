<x-layouts.crm :title="$candidate->exists ? 'Edit student' : 'Add student'">
    <x-page-header :title="$candidate->exists ? $candidate->full_name : 'Add student'"
                   subtitle="Everything except name and email can be filled in later." />

    @if(session('duplicates'))
        <div class="mb-4 rounded-xl border border-warning/40 bg-warning/10 p-4">
            <p class="font-medium">Possible duplicate</p>
            <ul class="mt-2 space-y-1 text-sm">
                @foreach(session('duplicates') as $match)
                    <li>
                        <a href="{{ route('candidates.show', $match['candidate']) }}" class="font-medium underline">
                            {{ $match['candidate']->full_name }} ({{ $match['candidate']->uid }})
                        </a>
                        <span class="text-muted">— {{ $match['reason'] }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="mt-2 text-sm text-muted">Save again to create this student anyway.</p>
        </div>
    @endif

    <form method="POST"
          action="{{ $candidate->exists ? route('candidates.update', $candidate) : route('candidates.store') }}"
          class="space-y-6">
        @csrf
        @if($candidate->exists) @method('PUT') @endif
        @if(session('duplicates')) <input type="hidden" name="confirm_duplicate" value="1"> @endif

        <fieldset class="rounded-xl border border-line bg-white p-6 shadow-glass">
            <legend class="px-2 font-display text-lg text-navy">Personal</legend>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    'first_name' => ['First name', 'text', true],
                    'last_name' => ['Last name', 'text', true],
                    'email' => ['Email', 'email', true],
                    'phone' => ['Phone', 'text', false],
                    'alt_phone' => ['Alternative phone', 'text', false],
                    'date_of_birth' => ['Date of birth', 'date', false],
                    'nationality' => ['Nationality', 'text', false],
                    'country_of_residence' => ['Country of residence', 'text', false],
                    'passport_number' => ['Passport number', 'text', false],
                    'passport_expiry' => ['Passport expiry', 'date', false],
                ] as $field => [$label, $type, $required])
                    <div>
                        <x-input-label :for="$field" :value="$label" />
                        <x-text-input :id="$field" :name="$field" :type="$type" class="mt-1 block w-full"
                                      :value="old($field, $type === 'date' ? $candidate->{$field}?->format('Y-m-d') : $candidate->{$field})"
                                      :required="$required" />
                        <x-input-error :messages="$errors->get($field)" class="mt-2" />
                    </div>
                @endforeach

                <div>
                    <x-input-label for="gender" value="Gender" />
                    <select id="gender" name="gender" class="mt-1 block w-full rounded-xl border-line">
                        <option value="">Not stated</option>
                        @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other', 'prefer_not_to_say' => 'Prefer not to say'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('gender', $candidate->gender) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset class="rounded-xl border border-line bg-white p-6 shadow-glass">
            <legend class="px-2 font-display text-lg text-navy">Study plan</legend>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    'preferred_destination' => 'Destination',
                    'preferred_level' => 'Level',
                    'preferred_subject' => 'Subject',
                    'preferred_intake' => 'Intake',
                ] as $field => $label)
                    <div>
                        <x-input-label :for="$field" :value="$label" />
                        <x-text-input :id="$field" :name="$field" class="mt-1 block w-full"
                                      :value="old($field, $candidate->{$field})" />
                    </div>
                @endforeach

                <div>
                    <x-input-label for="budget_per_year" value="Budget per year" />
                    <x-text-input id="budget_per_year" name="budget_per_year" type="number" step="0.01"
                                  class="mt-1 block w-full" :value="old('budget_per_year', $candidate->budget_per_year)" />
                </div>

                <div>
                    <x-input-label for="budget_currency" value="Currency" />
                    <select id="budget_currency" name="budget_currency" class="mt-1 block w-full rounded-xl border-line">
                        <option value="">Not set</option>
                        @foreach(settings('finance.currencies', ['GBP']) as $currency)
                            <option value="{{ $currency }}" @selected(old('budget_currency', $candidate->budget_currency) === $currency)>{{ $currency }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset class="rounded-xl border border-line bg-white p-6 shadow-glass">
            <legend class="px-2 font-display text-lg text-navy">Ownership</legend>

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @can('assign', App\Models\Candidate::class)
                    <div>
                        <x-input-label for="assigned_to" value="Consultant" />
                        <select id="assigned_to" name="assigned_to" class="mt-1 block w-full rounded-xl border-line">
                            <option value="">Unassigned</option>
                            @foreach($consultants as $consultant)
                                <option value="{{ $consultant->id }}" @selected(old('assigned_to', $candidate->assigned_to) == $consultant->id)>{{ $consultant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcan

                <div>
                    <x-input-label for="branch_id" value="Office" />
                    <select id="branch_id" name="branch_id" class="mt-1 block w-full rounded-xl border-line">
                        <option value="">Not set</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(old('branch_id', $candidate->branch_id) == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full rounded-xl border-line">
                        @foreach(['new', 'in_progress', 'applied', 'enrolled', 'lost'] as $status)
                            <option value="{{ $status }}" @selected(old('status', $candidate->status ?? 'new') === $status)>{{ Str::headline($status) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="source" value="Source" />
                    <x-text-input id="source" name="source" class="mt-1 block w-full" :value="old('source', $candidate->source)" />
                </div>
            </div>

            <div class="mt-5">
                <x-input-label for="notes" value="Notes" />
                <textarea id="notes" name="notes" rows="4"
                          class="mt-1 block w-full rounded-xl border-line focus:border-orange focus:ring-orange">{{ old('notes', $candidate->notes) }}</textarea>
            </div>
        </fieldset>

        <div class="flex items-center gap-3">
            <x-primary-button>{{ $candidate->exists ? 'Save changes' : 'Create student' }}</x-primary-button>
            <a href="{{ $candidate->exists ? route('candidates.show', $candidate) : route('candidates.index') }}"
               class="text-sm text-muted underline">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
