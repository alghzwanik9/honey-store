@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-black text-sm text-gray-200 mb-2 tracking-wide']) }}>
    {{ $value ?? $slot }}
</label>
