<x-layout title="سلة المشتريات">
    <div class="container mx-auto px-4 py-16 md:py-24" dir="rtl">
        <h1 class="text-3xl font-bold text-brand-navy mb-12 text-center">سلة المشتريات</h1>

        @if (empty($cart) || count($cart) == 0)
            <div
                class="bg-brand-cream-light rounded-3xl shadow-2xl p-20 text-center border border-brand-gold/10 relative overflow-hidden">
                <div
                    class="absolute top-0 right-0 w-full h-1.5 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold">
                </div>
                <div class="mb-8">
                    <svg class="w-24 h-24 mx-auto text-brand-gold/20" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-brand-navy mb-4 leading-relaxed">سلتك بانتظار عسلنا الفاخر</h2>
                <p class="text-base text-gray-500 mb-10 leading-relaxed">تبدو سلة مشترياتك خالية حالياً، ما رأيك باكتشاف
                    تشكيلتنا؟</p>
                <a href="/"
                    class="bg-brand-navy text-brand-gold hover:bg-brand-gold hover:text-brand-navy font-semibold py-4 px-12 rounded-xl transition-all shadow-xl inline-block">ابدأ
                    التسوق الآن</a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="lg:w-2/3">
                    <div class="bg-brand-cream-light rounded-3xl shadow-xl overflow-hidden border border-brand-gold/10">
                        @foreach ($cart as $id => $details)
                            <div
                                class="flex items-center p-6 border-b border-brand-gold/5 last:border-b-0 hover:bg-brand-beige/30 transition-colors">
                                <!-- Image -->
                                <div
                                    class="w-24 h-24 shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                    @if ($details['image_url'])
                                        <img src="{{ Storage::url('products/' . $details['image_url']) }}"
                                            alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Details -->
                                <div class="grow mr-6">
                                    <h3 class="text-base font-semibold text-brand-navy mb-2 leading-relaxed">
                                        <a href="{{ route('products.show', ['product' => $details['slug']]) }}"
                                            class="hover:text-brand-gold transition-colors">
                                            {{ $details['name'] }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-400 mb-2 font-medium">الكمية: {{ $details['quantity'] }}
                                    </p>
                                    <div class="flex items-center gap-1 font-bold text-brand-navy">
                                        <span>{{ number_format($details['price']) }}</span>
                                        <x-currency-symbol class="text-brand-gold" />
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col items-end justify-between self-stretch">
                                    <div
                                        class="text-lg font-bold text-brand-navy mb-2 flex items-center gap-1 leading-none">
                                        {{ number_format($details['price'] * $details['quantity']) }}
                                        <x-currency-symbol class="text-brand-gold" />
                                    </div>

                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-full transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div
                        class="bg-brand-cream-light rounded-3xl shadow-xl p-8 border border-brand-gold/10 sticky top-28 overflow-hidden">
                        <div class="absolute top-0 right-0 w-full h-1 bg-brand-gold"></div>
                        <h2
                            class="text-lg font-bold text-brand-navy mb-6 border-b border-brand-gold/10 pb-4 leading-relaxed">
                            ملخص الطلب</h2>

                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-500 font-medium">
                                <span>المجموع الفرعي</span>
                                <span class="flex items-center gap-1 text-brand-navy">
                                    {{ number_format($total) }}
                                    <x-currency-symbol class="text-brand-gold/50" />
                                </span>
                            </div>
                            <div
                                class="flex justify-between text-lg font-bold text-brand-navy border-t border-brand-gold/10 pt-6 mt-6">
                                <span>الإجمالي</span>
                                <div class="flex items-center gap-1 text-brand-gold">
                                    {{ number_format($total) }}
                                    <x-currency-symbol />
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}"
                            class="w-full bg-brand-navy text-brand-gold hover:bg-brand-gold hover:text-brand-navy font-semibold text-base py-4 rounded-xl shadow-xl transition-all transform active:scale-95 flex items-center justify-center gap-2 group">
                            <span>إتمام الطلب</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>

                        <div class="mt-6 text-center">
                            <a href="/"
                                class="text-brand-gold hover:text-brand-gold-dark text-sm font-bold transition-colors">متابعة
                                التسوق</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>
