@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-brand-gold focus:ring-brand-gold rounded-xl shadow-sm transition-all duration-300']) }}>
