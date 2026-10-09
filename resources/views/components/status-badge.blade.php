@props(['status', 'tone' => null])

@php
    // Status strings come from several modules, so map on intent not exact value.
    $value = strtolower((string) $status);

    $tone ??= match (true) {
        str_contains($value, 'reject'), str_contains($value, 'refus'),
        str_contains($value, 'expired'), str_contains($value, 'overdue'),
        str_contains($value, 'withdraw'), str_contains($value, 'clawed') => 'danger',

        str_contains($value, 'pending'), str_contains($value, 'await'),
        str_contains($value, 'review'), str_contains($value, 'draft'),
        str_contains($value, 'required') => 'warning',

        str_contains($value, 'verified'), str_contains($value, 'approved'),
        str_contains($value, 'paid'), str_contains($value, 'enrolled'),
        str_contains($value, 'issued'), str_contains($value, 'complete') => 'success',

        default => 'neutral',
    };

    $classes = [
        'success' => 'bg-success/10 text-success ring-success/20',
        'warning' => 'bg-warning/10 text-warning ring-warning/20',
        'danger' => 'bg-danger/10 text-danger ring-danger/20',
        'neutral' => 'bg-navy/5 text-navy ring-navy/10',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 '.$classes[$tone]]) }}>
    {{ Str::headline(str_replace('_', ' ', (string) $status)) }}
</span>
