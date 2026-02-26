<x-layout title="الرئيسية">


    {{-- ===== PRODUCTS SECTION ===== --}}
    <section id="products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14" dir="rtl">

        {{-- Page Header when search/filter active --}}
        @if(request('category') || request('search'))
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('home') }}" class="text-brand-gold hover:text-brand-gold-dark font-bold flex items-center gap-1 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                الرئيسية
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-600 font-black">
                @if(request('search'))
                    نتائج البحث عن: "{{ request('search') }}"
                @elseif(request('category'))
                    @php
                        $foundCat = collect($honeyCategories ?? [])->firstWhere('slug', request('category'));
                        $catName = $foundCat ? $foundCat->name : request('category');
                    @endphp
                    تصفية: {{ $catName }}
                @endif
            </span>
        </div>
        @endif

        {{-- Section Title --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-gray-900">
                    @if(request('search'))
                        نتائج البحث
                    @elseif(request('category'))
                        المنتجات المختارة
                    @else
                        تشكيلة عسلنا الفاخرة
                    @endif
                </h2>
                <p class="text-gray-400 text-sm mt-1">{{ $products->total() }} منتج متاح</p>
            </div>

            {{-- Sort / View toggle placeholder --}}
            <div class="hidden md:flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4 text-brand-gold" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l-4-4 1.41-1.41L9 9.17l4.59-4.58L15 6l-6 6z"/>
                </svg>
                جودة مضمونة
            </div>
        </div>

        {{-- Products Grid --}}
        @if($products->count())
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5 md:gap-7">
            @foreach($products as $index => $product)
                @php
                    $staggerClass = 'stagger-' . (($index % 4) + 1);
                    $isLowStock = $product->stock > 0 && $product->stock <= 5;
                @endphp

                <div class="bg-brand-cream-light rounded-2xl shadow-sm border border-brand-gold/5 overflow-hidden flex flex-col h-full hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300 group animate-fade-in-up {{ $staggerClass }}">
                    <a href="{{ route('products.show', $product) }}" class="flex flex-col grow">

                        {{-- صورة المنتج --}}
                        <div class="relative h-48 md:h-60 w-full bg-gray-50 overflow-hidden">

                            {{-- Badge: نفد قريباً --}}
                            @if($isLowStock)
                            <div class="absolute top-2 right-2 z-10">
                                <span class="bg-red-500 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-md animate-pulse">
                                    ينفد قريباً
                                </span>
                            </div>
                            @endif

                            {{-- Weight Label --}}
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center z-10 pointer-events-none">
                                @if($product->weight)
                                    @php
                                        $categoryName = $product->category ? $product->category->name : '';
                                        $badgeClasses = 'bg-brand-navy/90 text-brand-gold border-brand-gold/30';

                                        if (Str::contains($categoryName, 'مجرى')) {
                                            $badgeClasses = 'bg-white/90 text-gray-900 border-gray-200 shadow-md';
                                        } elseif (Str::contains($categoryName, ['سلام', 'سمرة'])) {
                                            $badgeClasses = 'bg-[#3E2723]/90 text-amber-100 border-[#5D4037] shadow-md';
                                        } elseif (Str::contains($categoryName, 'سدر')) {
                                            $badgeClasses = 'bg-[#D2691E]/90 text-white border-[#A0522D] shadow-md';
                                        } elseif (Str::contains($categoryName, 'قتاد')) {
                                            $badgeClasses = 'bg-[#FFD700]/90 text-amber-900 border-[#FFC107] shadow-md';
                                        } elseif (Str::contains($categoryName, 'ضهيانة')) {
                                            $badgeClasses = 'bg-[#FBC02D]/90 text-gray-900 border-[#F9A825] shadow-md';
                                        } elseif (Str::contains($categoryName, 'مورينقا')) {
                                            $badgeClasses = 'bg-brand-gold/20 backdrop-blur-md text-brand-gold-dark border-brand-gold shadow-sm';
                                        }
                                    @endphp
                                    <span class="{{ $badgeClasses }} text-[10px] font-bold px-3 py-0.5 rounded-full border shadow-lg tracking-wide backdrop-blur-sm">
                                        {{ $product->weight }}
                                    </span>
                                @endif
                            </div>

                            {{-- صورة --}}
                            @php
                                $imageUrl = $product->image_url ? Storage::url($product->image_url) : ($product->category && $product->category->image ? Storage::url($product->category->image) : null);
                            @endphp

                            @if($imageUrl)
                                <img src="{{ $imageUrl }}"
                                     alt="{{ $product->name }}"
                                     loading="{{ $loop->index < 4 ? 'eager' : 'lazy' }}"
                                     class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-200">
                                    <div class="text-center">
                                        <span class="text-5xl block mb-2">🍯</span>
                                        <span class="text-xs text-gray-300">لا توجد صورة</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- معلومات المنتج --}}
                        <div class="p-3 sm:p-4 text-center flex flex-col items-center justify-between grow bg-brand-cream/50">
                            <div class="w-full">
                                @if($product->category)
                                    <span class="text-[10px] font-bold tracking-wider text-brand-navy bg-brand-gold/10 border border-brand-gold/20 px-2.5 py-1 rounded-full mb-2 inline-block">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                                <h2 class="text-base md:text-lg font-black text-brand-navy mb-1 group-hover:text-brand-gold transition-colors line-clamp-2 leading-snug">
                                    {{ $product->name }}
                                </h2>
                            </div>

                            {{-- السعر --}}
                            <div class="flex items-center justify-center gap-1.5 mt-3 bg-brand-beige/50 px-4 py-1 rounded-full border border-brand-gold/5">
                                <span class="text-brand-navy font-black text-2xl tabular-nums">{{ number_format($product->price) }}</span>
                                <x-currency-symbol class="w-6 h-6 text-brand-gold" />
                            </div>
                        </div>
                    </a>

                    {{-- زر الإضافة للسلة --}}
                    <div class="px-3 sm:px-4 pb-4 bg-brand-cream/50">
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                    class="w-full bg-brand-navy text-white font-bold py-3 px-4 rounded-xl hover:bg-brand-gold hover:text-brand-navy transition-all duration-300 flex items-center justify-center gap-2 group/btn shadow-lg hover:shadow-brand-gold/20 btn-glow">
                                <span>أضف للسلة</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover/btn:scale-110 transition-transform text-brand-gold group-hover/btn:text-brand-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @else
        {{-- حالة عدم وجود منتجات --}}
        <div class="text-center py-24" dir="rtl">
            <div class="text-7xl mb-6">🔍</div>
            <h3 class="text-2xl font-black text-gray-800 mb-3">لم يتم العثور على منتجات</h3>
            <p class="text-gray-400 mb-6">جرب البحث بكلمة أخرى أو تصفح جميع المنتجات</p>
            <a href="{{ route('home') }}" class="bg-brand-gold hover:bg-brand-gold-dark text-brand-navy font-bold px-8 py-3 rounded-full transition-all shadow-lg inline-block">
                عرض كل المنتجات
            </a>
        </div>
        @endif

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>

    </section>


</x-layout>