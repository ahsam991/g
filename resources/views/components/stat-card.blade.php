@props(['label', 'value', 'trend' => null, 'href' => null, 'tone' => 'navy'])

@php
    $tones = [
        'navy' => 'text-navy',
        'orange' => 'text-orange',
        'success' => 'text-success',
        'warning' => 'text-warning',
        'danger' => 'text-danger',
    ];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif
    class="block rounded-xl border border-line bg-white p-5 shadow-glass transition-transform duration-300 ease-rise @if($href) hover:-translate-y-1 @endif">
    <p class="text-sm text-muted">{{ $label }}</p>
    <p class="mt-1 font-display text-3xl {{ $tones[$tone] ?? $tones['navy'] }}">{{ $value }}</p>

    @if($trend !== null)
        <p class="mt-1 text-xs {{ $trend >= 0 ? 'text-success' : 'text-danger' }}">
            {{ $trend >= 0 ? '+' : '' }}{{ $trend }}% on last month
        </p>
    @endif
</{{ $tag }}>
