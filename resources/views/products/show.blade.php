<x-layout title="{{ $product->name }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-16" dir="rtl">
        
        {{-- Breadcrumbs --}}
        <nav class="flex mb-8 text-sm font-medium items-center gap-2 text-gray-400">
            <a href="/" class="hover:text-brand-gold transition-colors">الرئيسية</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            @if($product->category)
                <a href="/?category={{ $product->category->slug }}" class="hover:text-brand-gold transition-colors">{{ $product->category->name }}</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            @endif
            <span class="text-brand-navy font-bold">{{ $product->name }}</span>
        </nav>

        <div class="bg-brand-cream-light rounded-[3rem] shadow-2xl overflow-hidden border border-brand-gold/10 relative mb-16">
            <div class="absolute top-0 right-0 w-full h-2 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
            <div class="flex flex-col lg:flex-row-reverse"> 
                
                {{-- قسم الصورة --}}
                <div class="lg:w-1/2 relative bg-white/50 h-[400px] md:h-[600px] overflow-hidden group flex items-center justify-center border-l border-brand-gold/5 p-8">
                     
                     {{-- Low Stock / Availability Badge --}}
                     <div class="absolute top-8 right-8 z-10 flex flex-col gap-2">
                        @if($product->stock > 0 && $product->stock <= 5)
                            <span class="bg-red-500 text-white text-[10px] md:text-sm font-black px-4 py-1.5 rounded-full shadow-lg animate-pulse">
                                كمية محدودة جداً!
                            </span>
                        @endif
                        @if($product->stock > 0)
                            <span class="bg-emerald-500/10 text-emerald-600 backdrop-blur-md border border-emerald-500/20 text-[10px] md:text-sm font-black px-4 py-1.5 rounded-full shadow-sm text-center">
                                متوفر في المخزن
                            </span>
                        @else
                            <span class="bg-red-500/10 text-red-600 backdrop-blur-md border border-red-500/20 text-[10px] md:text-sm font-black px-4 py-1.5 rounded-full shadow-sm text-center">
                                نفذت الكمية
                            </span>
                        @endif
                     </div>

                     @php
                        // Image Logic: Product Image > Category Image > Placeholder
                        $imageUrl = $product->image_url ? Storage::url($product->image_url) : ($product->category && $product->category->image ? Storage::url($product->category->image) : null);
                    @endphp

                     @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain transform group-hover:scale-110 transition-transform duration-1000 drop-shadow-2xl">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300 flex-col gap-4">
                             <div class="w-32 h-32 bg-brand-cream rounded-full flex items-center justify-center">
                                <span class="text-6xl">🍯</span>
                             </div>
                            <span class="text-lg font-bold text-brand-navy/30">لا توجد صورة متوفرة</span>
                        </div>
                    @endif
                </div>
                
                {{-- قسم المحتوى --}}
                <div class="lg:w-1/2 p-8 md:p-16 flex flex-col items-start text-right">
                    <div class="w-full">
                        @if($product->category)
                            <span class="text-brand-gold font-black text-xs uppercase tracking-[0.2em] mb-4 inline-block bg-brand-gold/10 px-4 py-1 rounded-full border border-brand-gold/20">
                                {{ $product->category->name }}
                            </span>
                        @endif
                        <h1 class="text-4xl md:text-5xl font-black text-brand-navy mb-8 leading-[1.2]">{{ $product->name }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-6 mb-10 bg-white/60 backdrop-blur-sm p-6 md:p-8 rounded-[2rem] w-full border border-brand-gold/10 shadow-sm relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-1 h-full bg-brand-gold"></div>
                            <div class="flex items-center gap-3">
                                <span class="text-gray-400 text-sm font-bold block mb-1">السعر</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-4xl md:text-5xl font-black text-brand-navy tracking-tighter">{{ number_format($product->price) }}</span>
                                    <x-currency-symbol class="w-10 h-10 text-brand-gold" />
                                </div>
                            </div>
                            
                            @if($product->weight)
                            <div class="mx-4 h-12 w-px bg-gray-200 hidden md:block"></div>
                            <div class="flex flex-col">
                                <span class="text-gray-400 text-sm font-bold block mb-1">الوزن</span>
                                <div class="flex items-center gap-2 text-brand-navy font-black text-xl">
                                    <svg class="w-6 h-6 text-brand-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1 m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2 m6 2l3-1 m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9 m-3-9l-6-2 m0-2v2 m0 16V5 m0 16H9 m3 0h3"></path></svg>
                                    <span>{{ $product->weight }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="prose prose-lg prose-brand-navy text-gray-600 mb-12 leading-relaxed max-w-none text-right">
                        <div class="flex items-center gap-2 mb-4">
                            <h3 class="text-xl font-black text-brand-navy m-0">عن هذا المنتج</h3>
                            <div class="flex-grow h-px bg-brand-gold/20"></div>
                        </div>
                        {!! $product->description !!}
                    </div>

                    {{-- Features Grid --}}
                    <div class="grid grid-cols-2 gap-4 w-full mb-12">
                        <div class="bg-brand-cream p-4 rounded-2xl border border-brand-gold/5 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-gold/20 flex items-center justify-center text-brand-gold shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-brand-navy">طبيعي 100%</span>
                        </div>
                        <div class="bg-brand-cream p-4 rounded-2xl border border-brand-gold/5 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-gold/20 flex items-center justify-center text-brand-gold shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-bold text-brand-navy">جودة مضمونة</span>
                        </div>
                    </div>

                    <div class="mt-auto w-full pt-8 border-t border-brand-gold/10">
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex flex-col sm:flex-row items-center gap-4 w-full">
                            @csrf
                            <div class="flex items-center bg-brand-navy rounded-2xl p-1 shrink-0 w-full sm:w-auto">
                                <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="p-3 text-brand-gold hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                </button>
                                <input type="number" id="quantity" name="quantity" min="1" max="{{ $product->stock }}" value="1" 
                                       class="w-16 bg-transparent border-none text-center font-black text-xl text-white focus:ring-0">
                                <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="p-3 text-brand-gold hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                            <button type="submit" 
                                    @if($product->stock <= 0) disabled @endif
                                    class="flex-1 w-full bg-brand-navy text-brand-gold hover:bg-brand-gold hover:text-brand-navy font-black py-5 px-8 rounded-2xl shadow-xl transition-all duration-500 flex items-center justify-center gap-3 group text-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                @if($product->stock > 0)
                                    <svg class="w-7 h-7 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span>إضافة إلى سلة التسوق</span>
                                @else
                                    <span>نفذت الكمية حالياً</span>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Products Section (Placeholder) --}}
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div class="mt-20">
                <div class="flex items-center justify-between mb-10">
                    <h2 class="text-3xl font-black text-brand-navy">منتجات قد تعجبك</h2>
                    <a href="/" class="text-brand-gold font-bold hover:underline">عرض الكل</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    {{-- Related products items would go here --}}
                </div>
            </div>
        @endif
    </div>
</x-layout>