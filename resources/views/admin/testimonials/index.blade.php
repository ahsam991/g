<x-layouts.crm title="Testimonials">
    <x-page-header title="Testimonials" description="Manage student success stories shown on the public site.">
        <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-orange px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-orange/90">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Testimonial
        </a>
    </x-page-header>

    @if(session('status'))
        <div class="mb-4 rounded-lg bg-success/10 px-4 py-3 text-sm text-success">{{ session('status') }}</div>
    @endif

    @if($testimonials->isEmpty())
        <x-empty-state title="No testimonials" description="Add student success stories." />
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($testimonials as $t)
                <div class="rounded-xl border border-line bg-white p-5 shadow-sm">
                    <div class="flex items-center gap-3">
                        @if($t->photo)
                            <img src="{{ asset('storage/' . $t->photo) }}" alt="" class="h-12 w-12 rounded-full object-cover">
                        @else
                            <span class="text-3xl">{{ $t->country_flag }}</span>
                        @endif
                        <div>
                            <p class="font-semibold text-ink">{{ $t->name }}</p>
                            <p class="text-xs text-orange">{{ $t->visa_type }}</p>
                        </div>
                    </div>
                    <p class="mt-3 text-sm text-muted">"{{ Str::limit($t->quote, 100) }}"</p>
                    <div class="mt-2 flex gap-0.5">
                        @for($s = 0; $s < $t->rating; $s++)
                            <svg class="h-3.5 w-3.5 text-orange" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    @if(!$t->active)
                        <span class="mt-2 inline-flex rounded-full bg-muted/10 px-2 py-0.5 text-xs font-medium text-muted">Inactive</span>
                    @endif
                    <div class="mt-4 flex items-center gap-2 border-t border-line pt-3">
                        <a href="{{ route('admin.testimonials.edit', $t) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-navy hover:bg-navy/5 transition">Edit</a>
                        <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 transition">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.crm>
