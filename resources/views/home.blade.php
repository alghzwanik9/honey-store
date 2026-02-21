<x-layout title="الرئيسية">

    {{-- ===== HERO SECTION ===== --}}
    @if(!request('category') && !request('search'))
    <section class="relative bg-honeycomb overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-amber-50/80 via-white/40 to-white pointer-events-none"></div>

        {{-- دوائر زخرفية --}}
        <div class="absolute -top-20 -right-20 w-80 h-80 bg-amber-200/30 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-16 w-60 h-60 bg-yellow-300/20 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative container mx-auto px-4 py-16 md:py-24 flex flex-col md:flex-row-reverse items-center gap-10 md:gap-16" dir="rtl">

            {{-- نص الهيرو --}}
            <div class="flex-1 text-center md:text-right animate-fade-in-up">
                <span class="inline-block bg-amber-100 text-amber-700 text-sm font-bold px-4 py-1.5 rounded-full mb-5 border border-amber-200 shadow-sm">
                    🍯 مناحل وادي الجرفة
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 leading-tight mb-5">
                    عسل طبيعي<br>
                    <span class="text-gradient-gold">من المنحل إليك</span>
                </h1>
                <p class="text-gray-500 text-lg leading-relaxed mb-8 max-w-md mx-auto md:mx-0">
                    أجود أنواع العسل البلدي الأصيل، معتنى به بيد الخبراء من مناحلنا النقية مباشرةً لمائدتك.
                </p>
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <a href="#products"
                       class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-8 py-3.5 rounded-full shadow-lg btn-glow transition-all duration-300 text-base flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 7H4l1-7z"/>
                        </svg>
                        تسوّق الآن
                    </a>
                    <a href="{{ route('cart.index') }}"
                       class="bg-white hover:bg-gray-50 text-gray-800 font-bold px-8 py-3.5 rounded-full shadow border border-gray-200 transition-all duration-300 text-base">
                        السلة
                    </a>
                </div>

                {{-- Statistics row --}}
                <div class="flex justify-center md:justify-start gap-8 mt-10 pt-8 border-t border-amber-100">
                    <div class="text-center">
                        <div class="text-2xl font-black text-amber-700">100%</div>
                        <div class="text-xs text-gray-500 font-medium mt-0.5">طبيعي</div>
                    </div>
                    <div class="w-px bg-amber-100"></div>
                    <div class="text-center">
                        <div class="text-2xl font-black text-amber-700">+500</div>
                        <div class="text-xs text-gray-500 font-medium mt-0.5">عميل راضٍ</div>
                    </div>
                    <div class="w-px bg-amber-100"></div>
                    <div class="text-center">
                        <div class="text-2xl font-black text-amber-700">7+</div>
                        <div class="text-xs text-gray-500 font-medium mt-0.5">أنواع عسل</div>
                    </div>
                </div>
            </div>

            {{-- أيقونة العسل الرسومية --}}
            <div class="flex-shrink-0 animate-fade-in-up stagger-2">
                <div class="relative w-60 h-60 md:w-72 md:h-72">
                    <div class="absolute inset-0 bg-amber-400/20 rounded-full blur-2xl"></div>
                    <div class="relative w-full h-full rounded-full bg-gradient-to-br from-amber-300 via-yellow-400 to-amber-600 flex items-center justify-center shadow-2xl border-4 border-white">
                        <svg class="w-28 h-28 text-white drop-shadow-xl" viewBox="0 0 100 100" fill="none">
                            {{-- Honeycomb icon --}}
                            <text x="50" y="65" text-anchor="middle" font-size="60" fill="white" opacity="0.95">🍯</text>
                        </svg>
                    </div>
                    {{-- Floating badges --}}
                    <div class="absolute -top-3 -left-3 bg-white rounded-2xl shadow-lg px-3 py-2 flex items-center gap-2 border border-amber-100">
                        <span class="text-lg">⭐</span>
                        <div>
                            <div class="text-xs font-black text-gray-800">4.9</div>
                            <div class="text-[10px] text-gray-400">تقييم</div>
                        </div>
                    </div>
                    <div class="absolute -bottom-3 -right-3 bg-amber-600 text-white rounded-2xl shadow-lg px-3 py-2">
                        <div class="text-xs font-black">شحن سريع</div>
                        <div class="text-[10px] opacity-80">24 ساعة</div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    @endif

    {{-- ===== PRODUCTS SECTION ===== --}}
    <section id="products" class="container mx-auto px-4 py-10 md:py-14" dir="rtl">

        {{-- Page Header when search/filter active --}}
        @if(request('category') || request('search'))
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('home') }}" class="text-amber-600 hover:text-amber-700 font-bold flex items-center gap-1 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                الرئيسية
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-gray-600 font-medium">
                @if(request('search'))
                    نتائج البحث عن: "{{ request('search') }}"
                @elseif(request('category'))
                    تصفية: {{ request('category') }}
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
                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l-4-4 1.41-1.41L9 9.17l4.59-4.58L15 6l-6 6z"/>
                </svg>
                جودة مضمونة
            </div>
        </div>

        {{-- Products Grid --}}
        @if($products->count())
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 md:gap-7">
            @foreach($products as $index => $product)
                @php
                    $staggerClass = 'stagger-' . (($index % 4) + 1);
                    $isLowStock = $product->stock > 0 && $product->stock <= 5;
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 group animate-fade-in-up {{ $staggerClass }}">
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
                                        $badgeClasses = 'bg-black/70 text-amber-400 border-amber-500/30';

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
                                            $badgeClasses = 'bg-amber-500/20 backdrop-blur-md text-amber-900 border-amber-500 shadow-sm';
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
                        <div class="p-4 text-center flex flex-col items-center justify-between grow">
                            <div class="w-full">
                                @if($product->category)
                                    <span class="text-[10px] font-bold tracking-wider text-amber-600 bg-amber-50 px-2 py-1 rounded-full mb-2 inline-block border border-amber-100">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                                <h2 class="text-base md:text-lg font-bold text-gray-900 mb-1 group-hover:text-amber-600 transition-colors line-clamp-2 leading-snug">
                                    {{ $product->name }}
                                </h2>
                            </div>

                            {{-- السعر --}}
                            <div class="flex items-center justify-center gap-1.5 mt-3">
                                <span class="text-gray-900 font-extrabold text-2xl tabular-nums">{{ number_format($product->price) }}</span>
                                <svg class="w-7 h-7 text-gray-800" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                    <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    {{-- زر الإضافة للسلة --}}
                    <div class="px-4 pb-4">
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                    class="w-full bg-gray-900 text-white font-bold py-3 px-4 rounded-xl hover:bg-amber-600 transition-all duration-300 flex items-center justify-center gap-2 group/btn shadow-sm hover:shadow-md btn-glow">
                                <span>أضف للسلة</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover/btn:scale-110 transition-transform text-amber-400 group-hover/btn:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <a href="{{ route('home') }}" class="bg-amber-600 hover:bg-amber-700 text-white font-bold px-8 py-3 rounded-full transition-colors inline-block">
                عرض كل المنتجات
            </a>
        </div>
        @endif

        {{-- Pagination --}}
        <div class="mt-12">
            {{ $products->links() }}
        </div>

    </section>

    {{-- WHY US SECTION REMOVED --}}
    @if(false)
    <section class="bg-gray-900 text-white py-16 mt-8" dir="rtl">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-black text-center mb-12">
                <span class="text-amber-400">لماذا</span> مناحل وادي الجرفة؟
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 text-center">
                @foreach([
                    ['icon' => '🌿', 'title' => '100% طبيعي', 'desc' => 'بدون إضافات أو حافظات'],
                    ['icon' => '🏔️', 'title' => 'من الجبل', 'desc' => 'مناحل في البيئة البكر'],
                    ['icon' => '📦', 'title' => 'شحن سريع', 'desc' => 'توصيل لجميع مناطق المملكة'],
                    ['icon' => '✅', 'title' => 'جودة مضمونة', 'desc' => 'نضمن لك الأصالة والجودة'],
                ] as $feature)
                <div class="group">
                    <div class="text-4xl mb-4 group-hover:scale-110 transition-transform duration-300 inline-block">
                        {{ $feature['icon'] }}
                    </div>
                    <h3 class="font-bold text-white mb-1 text-base">{{ $feature['title'] }}</h3>
                    <p class="text-gray-400 text-sm">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</x-layout>