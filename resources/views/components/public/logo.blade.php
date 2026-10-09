@props(['dark' => false])

@php
    // Uses your logo file the moment it is dropped into public/images/.
    // Falls back to a badge + wordmark until then.
    $logo = public_path('images/logo.png');
    $hasLogo = file_exists($logo);
    $textColor = $dark ? 'text-white' : 'text-navy';
@endphp

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'flex items-center gap-2.5']) }}>
    @if($hasLogo)
        <img src="{{ asset('images/logo.png') }}" alt="{{ settings('company.name', 'Global Consultancy') }}"
             class="h-11 w-auto" width="160" height="44">
    @else
        <span class="grid h-10 w-10 place-items-center rounded-full bg-orange font-display text-xl text-navy">G</span>
        <span class="font-display text-xl leading-none {{ $textColor }}">Global<br><span class="text-orange">Consultancy</span></span>
    @endif
</a>
