<x-layouts.crm title="Services">
    <x-page-header title="Services" description="Manage services displayed on the public website.">
        <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-orange px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-orange/90">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Service
        </a>
    </x-page-header>

    @if(session('status'))
        <div class="mb-4 rounded-lg bg-success/10 px-4 py-3 text-sm text-success">{{ session('status') }}</div>
    @endif

    @if($services->isEmpty())
        <x-empty-state title="No services" description="Add services to show on the public website." />
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($services as $service)
                <div class="rounded-xl border border-line bg-white p-5 shadow-sm">
                    @if($service->icon_svg)
                        <div class="mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-navy/5 text-navy">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $service->icon_svg }}"/></svg>
                        </div>
                    @endif
                    <h3 class="font-semibold text-ink">{{ $service->title }}</h3>
                    <p class="mt-1 text-sm text-muted">{{ Str::limit($service->short_description, 80) }}</p>
                    <div class="mt-3 flex items-center gap-2">
                        @if(!$service->active)
                            <span class="inline-flex rounded-full bg-muted/10 px-2 py-0.5 text-xs font-medium text-muted">Inactive</span>
                        @endif
                        @if($service->featured)
                            <span class="inline-flex rounded-full bg-orange/10 px-2 py-0.5 text-xs font-medium text-orange">Featured</span>
                        @endif
                    </div>
                    <div class="mt-4 flex items-center gap-2 border-t border-line pt-3">
                        <a href="{{ route('admin.services.edit', $service) }}" class="rounded-lg px-3 py-1.5 text-xs font-medium text-navy hover:bg-navy/5 transition">Edit</a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')">
                            @csrf @method('DELETE')
                            <button class="rounded-lg px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50 transition">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layouts.crm>
