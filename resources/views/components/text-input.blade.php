@props(['disabled' => false, 'prefix' => null])

@php
    $classes = 'block w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-amber-400 focus:ring-amber-400/30 rounded-xl shadow-sm text-sm transition-all duration-200';
    if ($prefix) {
        $classes .= ' pl-11';
    }
@endphp

<div class="relative">
    @if ($prefix)
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
            {!! $prefix !!}
        </div>
    @endif
    <input @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>
</div>
