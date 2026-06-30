@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-gray-700 mb-1.5']) }}>
    {{ $value ?? $slot }}
    @if ($required)
        <span class="text-red-500 ml-0.5">*</span>
    @endif
</label>
