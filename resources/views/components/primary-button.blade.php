<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-brand-navy border border-brand-gold/20 rounded-xl font-black text-xs text-brand-gold uppercase tracking-widest hover:bg-brand-gold hover:text-brand-navy focus:bg-brand-gold-dark focus:text-brand-navy active:bg-brand-navy focus:outline-none focus:ring-2 focus:ring-brand-gold focus:ring-offset-2 transition ease-in-out duration-300 shadow-xl transform active:scale-[0.98]']) }}>
    {{ $slot }}
</button>
