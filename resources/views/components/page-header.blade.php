@props(['title', 'subtitle' => null])

<div class="mb-6 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="font-display text-2xl text-navy">{{ $title }}</h2>
        @if($subtitle)
            <p class="mt-1 text-sm text-muted">{{ $subtitle }}</p>
        @endif
    </div>

    @if(isset($actions))
        <div class="flex gap-2">{{ $actions }}</div>
    @endif
</div>
