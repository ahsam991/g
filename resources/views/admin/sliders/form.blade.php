<x-layouts.crm :title="$slider->exists ? 'Edit Slide' : 'New Slide'">
    <x-page-header :title="$slider->exists ? 'Edit Slide' : 'New Slide'" :description="$slider->exists ? 'Update this hero slide.' : 'Add a new slide to the homepage carousel.'" />

    <form method="POST"
          action="{{ $slider->exists ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}"
          enctype="multipart/form-data"
          class="mx-auto max-w-2xl space-y-6 rounded-xl border border-line bg-white p-6 shadow-sm">
        @csrf
        @if($slider->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-ink">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $slider->title) }}" required
                   class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm text-ink shadow-sm focus:border-navy focus:ring-navy">
            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Subtitle</label>
            <textarea name="subtitle" rows="2"
                      class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm text-ink shadow-sm focus:border-navy focus:ring-navy">{{ old('subtitle', $slider->subtitle) }}</textarea>
            @error('subtitle') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Badge Text</label>
            <input type="text" name="badge_text" value="{{ old('badge_text', $slider->badge_text) }}" placeholder="e.g. Admission Open for January 2026"
                   class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm text-ink shadow-sm focus:border-navy focus:ring-navy">
            @error('badge_text') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-ink">CTA Button Text</label>
                <input type="text" name="cta_text" value="{{ old('cta_text', $slider->cta_text) }}" placeholder="Book Free Counselling"
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm text-ink shadow-sm focus:border-navy focus:ring-navy">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">CTA Button Link</label>
                <input type="text" name="cta_link" value="{{ old('cta_link', $slider->cta_link) }}" placeholder="/contact"
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm text-ink shadow-sm focus:border-navy focus:ring-navy">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-ink">Second Button Text</label>
                <input type="text" name="cta2_text" value="{{ old('cta2_text', $slider->cta2_text) }}" placeholder="Check Eligibility"
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm text-ink shadow-sm focus:border-navy focus:ring-navy">
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Second Button Link</label>
                <input type="text" name="cta2_link" value="{{ old('cta2_link', $slider->cta2_link) }}" placeholder="/courses"
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm text-ink shadow-sm focus:border-navy focus:ring-navy">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Background Image</label>
            @if($slider->image)
                <div class="mt-2 mb-3">
                    <img src="{{ asset('storage/' . $slider->image) }}" alt="" class="h-32 w-56 rounded-xl object-cover shadow-sm">
                </div>
            @endif
            <input type="file" name="image" accept="image/*"
                   class="mt-1 block w-full text-sm text-muted file:mr-4 file:rounded-lg file:border-0 file:bg-navy/5 file:px-4 file:py-2 file:text-sm file:font-medium file:text-navy hover:file:bg-navy/10">
            <p class="mt-1 text-xs text-muted">Max 5 MB. Recommended: 1920x800px.</p>
            @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <label class="relative inline-flex cursor-pointer items-center">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" {{ old('active', $slider->active ?? true) ? 'checked' : '' }}
                       class="peer sr-only">
                <div class="h-6 w-11 rounded-full bg-line after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-navy peer-checked:after:translate-x-full peer-focus:ring-2 peer-focus:ring-navy/20"></div>
            </label>
            <span class="text-sm text-ink">Active</span>
        </div>

        <div class="flex items-center gap-3 border-t border-line pt-6">
            <button type="submit" class="rounded-lg bg-navy px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-navy-700">
                {{ $slider->exists ? 'Update Slide' : 'Create Slide' }}
            </button>
            <a href="{{ route('admin.sliders.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-muted transition hover:text-ink">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
