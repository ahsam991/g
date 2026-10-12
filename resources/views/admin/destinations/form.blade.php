<x-layouts.crm :title="$destination->exists ? 'Edit Destination' : 'New Destination'">
    <x-page-header :title="$destination->exists ? 'Edit Destination' : 'New Destination'" />

    <form method="POST"
          action="{{ $destination->exists ? route('admin.destinations.update', $destination) : route('admin.destinations.store') }}"
          enctype="multipart/form-data"
          class="mx-auto max-w-2xl space-y-6 rounded-xl border border-line bg-white p-6 shadow-sm">
        @csrf
        @if($destination->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-ink">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $destination->name) }}" required
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-ink">Flag Emoji</label>
                <input type="text" name="flag" value="{{ old('flag', $destination->flag) }}" placeholder="🇬🇧"
                       class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Short Description</label>
            <input type="text" name="short_description" value="{{ old('short_description', $destination->short_description) }}"
                   class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Full Description</label>
            <textarea name="description" rows="4"
                      class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">{{ old('description', $destination->description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Image</label>
            @if($destination->image)
                <img src="{{ asset('storage/' . $destination->image) }}" alt="" class="mt-2 mb-3 h-24 w-40 rounded-xl object-cover">
            @endif
            <input type="file" name="image" accept="image/*"
                   class="mt-1 block w-full text-sm text-muted file:mr-4 file:rounded-lg file:border-0 file:bg-navy/5 file:px-4 file:py-2 file:text-sm file:font-medium file:text-navy hover:file:bg-navy/10">
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Card Gradient Classes</label>
            <input type="text" name="gradient_classes" value="{{ old('gradient_classes', $destination->gradient_classes) }}" placeholder="from-blue-50 to-red-50"
                   class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" {{ old('active', $destination->active ?? true) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-line text-navy focus:ring-navy">
                <span class="text-sm text-ink">Active</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="hidden" name="featured" value="0">
                <input type="checkbox" name="featured" value="1" {{ old('featured', $destination->featured) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-line text-orange focus:ring-orange">
                <span class="text-sm text-ink">Featured on homepage</span>
            </label>
        </div>

        <div class="flex items-center gap-3 border-t border-line pt-6">
            <button type="submit" class="rounded-lg bg-navy px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-navy-700">
                {{ $destination->exists ? 'Update' : 'Create' }}
            </button>
            <a href="{{ route('admin.destinations.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-muted transition hover:text-ink">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
