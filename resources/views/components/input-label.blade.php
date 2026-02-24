@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-black text-sm text-brand-navy mb-1']) }}>
    {{ $value ?? $slot }}
</label>
