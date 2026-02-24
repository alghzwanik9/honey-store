<x-layout title="سلة المشتريات">
    <div class="container mx-auto px-4 py-12" dir="rtl">
        <h1 class="text-3xl md:text-4xl font-black text-brand-navy mb-12 text-center">سلة المشتريات</h1>

        @if(empty($cart) || count($cart) == 0)
            <div class="bg-white rounded-2xl shadow-xl p-16 text-center border border-brand-gold/10">
                <svg class="w-20 h-20 mx-auto text-gray-200 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <p class="text-xl text-gray-400 mb-8">سلة مشترياتك فارغة حالياً.</p>
                <a href="/" class="bg-brand-gold hover:bg-brand-gold-dark text-brand-navy font-bold py-3 px-10 rounded-xl transition-all shadow-lg inline-block">تصفح المنتجات</a>
            </div>
        @else
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Cart Items -->
                <div class="lg:w-2/3">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-brand-gold/10">
                        @foreach($cart as $id => $details)
                            <div class="flex items-center p-6 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">
                                <!-- Image -->
                                <div class="w-24 h-24 shrink-0 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                    @if($details['image_url'])
                                        <img src="{{ Storage::url($details['image_url']) }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Details -->
                                <div class="grow mr-6">
                                    <h3 class="text-lg font-bold text-brand-navy mb-1 leading-snug">
                                        <a href="{{ route('products.show', ['product' => $details['slug']]) }}" class="hover:text-brand-gold transition-colors">
                                            {{ $details['name'] }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-400 mb-2 font-medium">الكمية: {{ $details['quantity'] }}</p>
                                    <div class="flex items-center gap-1 font-black text-brand-gold">
                                        <span>{{ number_format($details['price']) }}</span>
                                        <span class="text-xs">
                                        <svg class="w-6 h-6 text-emerald-600 inline-block drop-shadow-sm" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                            <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                        </svg>
                                    </span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col items-end justify-between self-stretch">
                                    <div class="text-lg font-black text-gray-900 mb-2">
                                        {{ number_format($details['price'] * $details['quantity']) }} 
                                        <span class="text-xs font-normal text-gray-500 inline-block align-middle">
                                            <svg class="w-6 h-6 text-emerald-600 inline-block drop-shadow-sm" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                                <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                            </svg>
                                        </span>
                                    </div>
                                    
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-full transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-brand-gold/10 sticky top-28">
                        <h2 class="text-xl font-black text-brand-navy mb-8 border-b border-brand-gold/10 pb-4">ملخص الطلب</h2>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>المجموع الفرعي</span>
                                <span class="font-medium flex items-center gap-3" style="gap: 0.75rem;">
                                    {{ number_format($total) }} 
                                    <svg class="w-3 h-3 text-emerald-700 mr-2" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                        <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>الضريبة (15%)</span>
                                <span class="font-medium flex items-center gap-3" style="gap: 0.75rem;">
                                    {{ number_format($total * 0.15) }} 
                                    <svg class="w-3 h-3 text-emerald-700 mr-2" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                        <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                    </svg>
                                </span>
                            </div>
                            <div class="flex justify-between text-xl font-black text-brand-navy border-t border-brand-gold/10 pt-6 mt-6">
                                <span>الإجمالي</span>
                                <div class="text-brand-gold">
                                    {{ number_format($total * 1.15) }} 
                                    <span class="text-sm font-normal inline-block">
                                        <svg class="w-6 h-6 text-brand-gold inline-block drop-shadow-sm mr-2" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                            <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="w-full bg-brand-navy text-brand-gold hover:bg-brand-gold hover:text-brand-navy font-black py-4 rounded-xl shadow-xl transition-all transform active:scale-95 flex items-center justify-center gap-2 group">
                            <span>إتمام الطلب</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        
                        <div class="mt-6 text-center">
                            <a href="/" class="text-brand-gold hover:text-brand-gold-dark text-sm font-bold transition-colors">متابعة التسوق</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layout>
