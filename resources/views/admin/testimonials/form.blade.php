<x-layouts.crm :title="$testimonial->exists ? 'Edit Testimonial' : 'New Testimonial'">
    <x-page-header :title="$testimonial->exists ? 'Edit Testimonial' : 'New Testimonial'" />

    <form method="POST"
          action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}"
          enctype="multipart/form-data"
          class="mx-auto max-w-2xl space-y-6 rounded-xl border border-line bg-white p-6 shadow-sm">
        @csrf
        @if($testimonial->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-ink">Student Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $testimonial->name) }}" required
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Country Flag</label>
                <input type="text" name="country_flag" value="{{ old('country_flag', $testimonial->country_flag) }}" placeholder="🇬🇧"
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Visa Type</label>
            <input type="text" name="visa_type" value="{{ old('visa_type', $testimonial->visa_type) }}" placeholder="UK Student Visa"
                   class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Quote <span class="text-red-500">*</span></label>
            <textarea name="quote" rows="3" required
                      class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">{{ old('quote', $testimonial->quote) }}</textarea>
            @error('quote') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-ink">Photo</label>
                @if($testimonial->photo)
                    <img src="{{ asset('storage/' . $testimonial->photo) }}" alt="" class="mt-2 mb-3 h-16 w-16 rounded-full object-cover">
                @endif
                <input type="file" name="photo" accept="image/*"
                       class="mt-1 block w-full text-sm text-muted file:mr-4 file:rounded-lg file:border-0 file:bg-navy/5 file:px-4 file:py-2 file:text-sm file:font-medium file:text-navy hover:file:bg-navy/10">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Rating</label>
                <select name="rating" class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? 5) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <label class="flex items-center gap-2">
            <input type="hidden" name="active" value="0">
            <input type="checkbox" name="active" value="1" {{ old('active', $testimonial->active ?? true) ? 'checked' : '' }}
                   class="h-4 w-4 rounded border-line text-navy focus:ring-navy">
            <span class="text-sm text-ink">Active</span>
        </label>

        <div class="flex items-center gap-3 border-t border-line pt-6">
            <button type="submit" class="rounded-lg bg-navy px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-navy-700">
                {{ $testimonial->exists ? 'Update' : 'Create' }}
            </button>
            <a href="{{ route('admin.testimonials.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-muted transition hover:text-ink">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
