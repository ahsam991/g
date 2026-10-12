<x-layouts.crm :title="$university->exists ? 'Edit University' : 'New University'">
    <x-page-header :title="$university->exists ? 'Edit University' : 'New University'" />

    <form method="POST"
          action="{{ $university->exists ? route('admin.universities.update', $university) : route('admin.universities.store') }}"
          enctype="multipart/form-data"
          class="mx-auto max-w-2xl space-y-6 rounded-xl border border-line bg-white p-6 shadow-sm">
        @csrf
        @if($university->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-ink">University Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $university->name) }}" required
                   class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-ink">Destination <span class="text-red-500">*</span></label>
                <select name="destination_id" required
                        class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
                    <option value="">Select destination</option>
                    @foreach($destinations as $dest)
                        <option value="{{ $dest->id }}" {{ old('destination_id', $university->destination_id) == $dest->id ? 'selected' : '' }}>{{ $dest->name }}</option>
                    @endforeach
                </select>
                @error('destination_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">City</label>
                <input type="text" name="city" value="{{ old('city', $university->city) }}"
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-ink">Website</label>
                <input type="url" name="website" value="{{ old('website', $university->website) }}" placeholder="https://..."
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Ranking</label>
                <input type="text" name="ranking" value="{{ old('ranking', $university->ranking) }}" placeholder="e.g. Top 50 UK"
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Short Description</label>
            <input type="text" name="short_description" value="{{ old('short_description', $university->short_description) }}"
                   class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Full Description</label>
            <textarea name="description" rows="4"
                      class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">{{ old('description', $university->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Logo</label>
            @if($university->logo)
                <img src="{{ asset('storage/' . $university->logo) }}" alt="" class="mt-2 mb-3 h-16 w-16 rounded-lg object-contain">
            @endif
            <input type="file" name="logo" accept="image/*"
                   class="mt-1 block w-full text-sm text-muted file:mr-4 file:rounded-lg file:border-0 file:bg-navy/5 file:px-4 file:py-2 file:text-sm file:font-medium file:text-navy hover:file:bg-navy/10">
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
                <input type="hidden" name="partner" value="0">
                <input type="checkbox" name="partner" value="1" {{ old('partner', $university->partner) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-line text-navy focus:ring-navy">
                <span class="text-sm text-ink">Partner University</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" {{ old('active', $university->active ?? true) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-line text-navy focus:ring-navy">
                <span class="text-sm text-ink">Active</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="hidden" name="featured" value="0">
                <input type="checkbox" name="featured" value="1" {{ old('featured', $university->featured) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-line text-orange focus:ring-orange">
                <span class="text-sm text-ink">Featured</span>
            </label>
        </div>

        <div class="flex items-center gap-3 border-t border-line pt-6">
            <button type="submit" class="rounded-lg bg-navy px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-navy-700">
                {{ $university->exists ? 'Update' : 'Create' }}
            </button>
            <a href="{{ route('admin.universities.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-muted transition hover:text-ink">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
