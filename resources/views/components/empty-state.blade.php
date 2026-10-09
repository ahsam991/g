@props(['title' => 'Nothing here yet', 'message' => null])

<div class="rounded-xl border border-dashed border-line bg-white px-6 py-14 text-center">
    <p class="font-display text-xl text-navy">{{ $title }}</p>

    @if($message)
        <p class="mx-auto mt-2 max-w-sm text-sm text-muted">{{ $message }}</p>
    @endif

    @if(isset($action))
        <div class="mt-5">{{ $action }}</div>
    @endif
</div>
