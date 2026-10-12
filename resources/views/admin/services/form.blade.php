<x-layouts.crm :title="$service->exists ? 'Edit Service' : 'New Service'">
    <x-page-header :title="$service->exists ? 'Edit Service' : 'New Service'" />

    <form method="POST"
          action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}"
          class="mx-auto max-w-2xl space-y-6 rounded-xl border border-line bg-white p-6 shadow-sm">
        @csrf
        @if($service->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm font-medium text-ink">Service Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $service->title) }}" required
                   class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">
            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Icon SVG Path</label>
            <textarea name="icon_svg" rows="2" placeholder="Paste Heroicons SVG path d=&quot;...&quot; value"
                      class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 font-mono text-xs shadow-sm focus:border-navy focus:ring-navy">{{ old('icon_svg', $service->icon_svg) }}</textarea>
            <p class="mt-1 text-xs text-muted">Get paths from <a href="https://heroicons.com" target="_blank" class="text-navy underline">heroicons.com</a></p>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Short Description</label>
            <textarea name="short_description" rows="2"
                      class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">{{ old('short_description', $service->short_description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-ink">Full Description</label>
            <textarea name="description" rows="4"
                      class="mt-1 block w-full rounded-lg border border-line bg-canvas px-4 py-2.5 text-sm shadow-sm focus:border-navy focus:ring-navy">{{ old('description', $service->description) }}</textarea>
        </div>

        <div class="flex items-center gap-6">
            <label class="flex items-center gap-2">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" value="1" {{ old('active', $service->active ?? true) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-line text-navy focus:ring-navy">
                <span class="text-sm text-ink">Active</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="hidden" name="featured" value="0">
                <input type="checkbox" name="featured" value="1" {{ old('featured', $service->featured) ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-line text-orange focus:ring-orange">
                <span class="text-sm text-ink">Featured on homepage</span>
            </label>
        </div>

        <div class="flex items-center gap-3 border-t border-line pt-6">
            <button type="submit" class="rounded-lg bg-navy px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-navy-700">
                {{ $service->exists ? 'Update' : 'Create' }}
            </button>
            <a href="{{ route('admin.services.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-muted transition hover:text-ink">Cancel</a>
        </div>
    </form>
</x-layouts.crm>
