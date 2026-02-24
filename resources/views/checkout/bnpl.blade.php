<x-layout title="إتمام الدفع">
    <div class="container mx-auto px-4 py-20" dir="rtl">
        <div class="max-w-md mx-auto bg-brand-cream-light rounded-3xl shadow-2xl overflow-hidden text-center p-12 border border-brand-gold/10 relative">
            <div class="absolute top-0 right-0 w-full h-1.5 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
            
            <div class="mb-10">
                @if($order->payment_method == 'tamara')
                    <div class="flex justify-center mb-4">
                        <div class="bg-[#c08d5d]/10 px-6 py-2 rounded-xl border border-[#c08d5d]/20">
                            <h2 class="text-3xl font-black" style="color: #c08d5d;">TAMARA</h2>
                        </div>
                    </div>
                    <p class="text-gray-400 font-medium">جاري تحويلك إلى بوابة دفع تمارا الآمنة...</p>
                @else
                    <div class="flex justify-center mb-4">
                        <div class="bg-emerald-50 px-6 py-2 rounded-xl border border-emerald-100">
                            <h2 class="text-3xl font-black text-emerald-600">tabby</h2>
                        </div>
                    </div>
                    <p class="text-gray-400 font-medium">جاري تحويلك إلى بوابة دفع تابي الآمنة...</p>
                @endif
            </div>

            <div class="flex justify-center mb-8">
                <svg class="animate-spin h-12 w-12 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <div class="bg-brand-beige/50 rounded-2xl p-8 mb-10 text-right border border-brand-gold/5 shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-1.5 h-full bg-brand-gold"></div>
                <div class="flex justify-between items-center mb-4 text-sm">
                    <span class="text-gray-500 font-bold">رقم الطلب:</span>
                    <span class="font-black text-brand-navy">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500 font-bold">إجمالي المبلغ:</span>
                    <span class="font-black text-brand-gold text-2xl flex items-center gap-2">
                        {{ number_format($order->total_amount) }} 
                        <x-currency-symbol class="w-8 h-8" />
                    </span>
                </div>
            </div>

            <p class="text-sm text-gray-400 mb-8">هذه صفحة محاكاة (Simulation) نظراً لعدم توفر حساب تاجر حقيقي.</p>

            <form action="{{ route('checkout.callback') }}" method="GET">
                <input type="hidden" name="id" value="simulated_payment_id_{{ $order->id }}">
                <input type="hidden" name="status" value="paid">
                <input type="hidden" name="message" value="Simulated Success">
                <input type="hidden" name="order_id" value="{{ $order->id }}"> <!-- Sending Order ID explicitly -->

                <button type="submit" class="w-full bg-brand-navy text-brand-gold hover:bg-brand-gold hover:text-brand-navy font-black py-4 px-8 rounded-xl shadow-xl transition-all duration-300 transform active:scale-[0.98]">
                    محاكاة دفع ناجح ✅
                </button>
            </form>
            
            <div class="mt-4">
                  <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-brand-gold font-bold transition-colors underline">إلغاء</a>
            </div>
        </div>
    </div>
</x-layout>
