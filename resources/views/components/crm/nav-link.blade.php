@props(['active' => false])

<a {{ $attributes->merge([
    'class' => 'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors '
        . ($active ? 'bg-white/10 font-medium text-orange' : 'text-white/80 hover:bg-white/5 hover:text-white'),
]) }} @if($active) aria-current="page" @endif>
    {{ $slot }}
</a>
