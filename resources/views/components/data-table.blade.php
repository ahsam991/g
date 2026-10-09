@props(['rows', 'searchable' => true, 'placeholder' => 'Search'])

<div class="overflow-hidden rounded-xl border border-line bg-white shadow-glass">
    @if($searchable)
        <form method="GET" class="flex flex-wrap items-center gap-2 border-b border-line p-4">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder }}"
                   class="min-w-0 flex-1 rounded-xl border-line text-sm focus:border-orange focus:ring-orange" />

            {{ $filters ?? '' }}

            <button class="rounded-xl bg-ink px-4 py-2 text-sm font-medium text-white transition-transform active:scale-[.98]">
                Search
            </button>

            @if(request()->hasAny(['q', 'status', 'assigned_to']))
                <a href="{{ url()->current() }}" class="px-2 text-sm text-muted underline">Clear</a>
            @endif
        </form>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-line text-sm">
            <thead class="bg-canvas text-left text-xs uppercase tracking-wide text-muted">
                <tr>{{ $head }}</tr>
            </thead>
            <tbody class="divide-y divide-line">
                {{ $body }}
            </tbody>
        </table>
    </div>

    @if($rows->hasPages())
        <div class="border-t border-line p-4">{{ $rows->withQueryString()->links() }}</div>
    @endif
</div>
