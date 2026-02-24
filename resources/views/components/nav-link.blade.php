@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-brand-gold text-sm font-black leading-5 text-brand-navy focus:outline-none transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold leading-5 text-gray-400 hover:text-brand-gold hover:border-brand-gold/30 focus:outline-none focus:text-brand-gold focus:border-brand-gold transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
