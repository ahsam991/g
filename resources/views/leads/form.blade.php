<x-layouts.crm>
    <x-slot:title>{{ $lead->exists ? 'Edit ' . $lead->uid : 'New lead' }}</x-slot:title>

    <x-page-header :title="$lead->exists ? 'Edit ' . $lead->uid : 'New lead'" />

    <form method="POST" action="{{ $lead->exists ? route('leads.update', $lead) : route('leads.store') }}"
          class="mx-auto max-w-3xl rounded-2xl border border-line bg-white p-6 shadow-glass sm:p-8">
        @csrf
        @if($lead->exists) @method('PUT') @endif

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="first_name" value="First name" />
                <x-text-input id="first_name" name="first_name" class="mt-1 block w-full" :value="old('first_name', $lead->first_name)" required />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="last_name" value="Last name" />
                <x-text-input id="last_name" name="last_name" class="mt-1 block w-full" :value="old('last_name', $lead->last_name)" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $lead->email)" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="phone" value="Phone" />
                <x-text-input id="phone" name="phone" class="mt-1 block w-full" :value="old('phone', $lead->phone)" />
            </div>
            <div>
                <x-input-label for="country" value="Country" />
                <x-text-input id="country" name="country" class="mt-1 block w-full" :value="old('country', $lead->country)" />
            </div>
            <div>
                <x-input-label for="lead_source_id" value="Source" />
                <select id="lead_source_id" name="lead_source_id" class="mt-1 block w-full rounded-xl border-line focus:border-orange focus:ring-orange">
                    <option value="">Select source</option>
                    @foreach($sources as $src)
                        <option value="{{ $src->id }}" @selected(old('lead_source_id', $lead->lead_source_id) == $src->id)>{{ $src->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="preferred_destination" value="Preferred destination" />
                <select id="preferred_destination" name="preferred_destination" class="mt-1 block w-full rounded-xl border-line focus:border-orange focus:ring-orange">
                    <option value="">Select</option>
                    @foreach(['United Kingdom', 'USA', 'Canada', 'Australia', 'Europe', 'Malaysia'] as $d)
                        <option @selected(old('preferred_destination', $lead->preferred_destination) === $d)>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="preferred_level" value="Preferred level" />
                <select id="preferred_level" name="preferred_level" class="mt-1 block w-full rounded-xl border-line focus:border-orange focus:ring-orange">
                    <option value="">Select</option>
                    @foreach(['Foundation', 'Undergraduate', 'Pre-Masters', 'Masters', 'PhD'] as $l)
                        <option @selected(old('preferred_level', $lead->preferred_level) === $l)>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="preferred_subject" value="Preferred subject" />
                <x-text-input id="preferred_subject" name="preferred_subject" class="mt-1 block w-full" :value="old('preferred_subject', $lead->preferred_subject)" />
            </div>
            @if($lead->exists)
                <div>
                    <x-input-label for="status" value="Status" />
                    <select id="status" name="status" class="mt-1 block w-full rounded-xl border-line focus:border-orange focus:ring-orange">
                        @foreach(App\Models\Lead::STATUSES as $s)
                            <option value="{{ $s }}" @selected(old('status', $lead->status) === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>

        <div class="mt-5">
            <x-input-label for="notes" value="Notes" />
            <textarea id="notes" name="notes" rows="3" class="mt-1 block w-full rounded-xl border-line focus:border-orange focus:ring-orange">{{ old('notes', $lead->notes) }}</textarea>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button class="rounded-xl bg-navy px-6 py-3 font-semibold text-white hover:bg-navy-700">{{ $lead->exists ? 'Update lead' : 'Create lead' }}</button>
            <a href="{{ $lead->exists ? route('leads.show', $lead) : route('leads.index') }}" class="text-sm text-muted hover:text-navy">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
