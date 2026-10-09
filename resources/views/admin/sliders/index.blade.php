<x-layouts.crm title="Sliders">
    <x-page-header title="Hero Sliders" description="Manage the homepage hero carousel slides.">
        <a href="{{ route('admin.sliders.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-orange px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-orange/90">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Slide
        </a>
    </x-page-header>

    @if(session('status'))
        <div class="mb-4 rounded-lg bg-success/10 px-4 py-3 text-sm text-success">{{ session('status') }}</div>
    @endif

    @if($sliders->isEmpty())
        <x-empty-state title="No slides yet" description="Add your first hero slide to show on the homepage." />
    @else
        <div class="overflow-hidden rounded-xl border border-line bg-white shadow-sm" x-data="sliderReorder()">
            <table class="min-w-full divide-y divide-line text-sm">
                <thead class="bg-canvas">
                    <tr>
                        <th class="w-10 px-4 py-3"></th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Image</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Title</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Badge</th>
                        <th class="px-4 py-3 text-left font-medium text-muted">Status</th>
                        <th class="px-4 py-3 text-right font-medium text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @foreach($sliders as $slider)
                        <tr class="hover:bg-canvas/50" data-id="{{ $slider->id }}">
                            <td class="px-4 py-3 text-center text-muted cursor-grab">
                                <svg class="mx-auto h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                            </td>
                            <td class="px-4 py-3">
                                @if($slider->image)
                                    <img src="{{ asset('storage/' . $slider->image) }}" alt="" class="h-12 w-20 rounded-lg object-cover">
                                @else
                                    <div class="flex h-12 w-20 items-center justify-center rounded-lg bg-canvas text-xs text-muted">No image</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-ink">{{ Str::limit($slider->title, 50) }}</td>
                            <td class="px-4 py-3 text-muted">{{ $slider->badge_text ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($slider->active)
                                    <span class="inline-flex items-center rounded-full bg-success/10 px-2.5 py-0.5 text-xs font-medium text-success">Active</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-muted/10 px-2.5 py-0.5 text-xs font-medium text-muted">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-navy hover:bg-navy/5 transition">Edit</a>
                                    <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}" onsubmit="return confirm('Delete this slide?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layouts.crm>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('sliderReorder', () => ({}));
});
</script>
@endpush
