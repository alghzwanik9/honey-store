<x-layout title="تم الطلب بنجاح">
    <div class="container mx-auto px-4 py-20" dir="rtl">
        <div class="max-w-2xl mx-auto bg-brand-navy-light rounded-[3rem] shadow-2xl overflow-hidden text-center p-16 border border-brand-gold/10 relative">
            <div class="absolute top-0 right-0 w-full h-2 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
            <div class="mb-10 flex flex-col items-center justify-center">
                <div class="transition-all duration-500 hover:scale-105">
                    <img src="/images/logo.png" class="h-10 md:h-14 w-auto object-contain" alt="مناحل وادي الجرفة">
                </div>
            </div>

            <h1 class="text-4xl font-black text-white mb-4">تهانينا! تم الطلب بنجاح</h1>
            <p class="text-xl text-gray-400 mb-10 leading-relaxed">نشكرك على ثقتك في مناحل وادي الجرفة. طلبك قيد التجهيز الآن بكل فخر.</p>

            <div class="bg-brand-navy rounded-3xl p-10 mb-12 text-right border border-gray-800 shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-1.5 h-full bg-brand-gold"></div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-400">رقم الطلب:</span>
                    <span class="font-bold text-gray-200 text-lg">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-gray-400 font-medium">حالة الدفع:</span>
                    <span class="font-bold {{ $order->payment_status === 'paid' ? 'text-emerald-400' : 'text-brand-gold' }}">
                        {{ $order->payment_status === 'paid' ? 'مدفوع بنجاح' : 'بانتظار الدفع / عند الاستلام' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold">الإجمالي النهائي:</span>
                    <span class="font-black text-white text-3xl flex items-center gap-2">
                        {{ number_format($order->total_amount) }} 
                        <x-currency-symbol class="w-10 h-10 text-brand-gold" />
                    </span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/" class="bg-brand-navy text-brand-gold hover:bg-brand-gold hover:text-brand-navy font-black py-4 px-10 rounded-xl shadow-xl transition-all duration-300">
                    العودة للرئيسية
                </a>
                <a href="{{ route('orders.index') }}" class="bg-white border-2 border-brand-gold/20 text-brand-navy hover:border-brand-gold font-black py-4 px-10 rounded-xl transition-all duration-300">
                   عرض طلباتي
                </a>
            </div>    
                @if($order->payment_status === 'paid')
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <a href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center gap-2 bg-green-50 text-green-700 border border-green-200 font-bold py-3 px-8 rounded-lg hover:bg-green-100 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        تحميل الفاتورة PDF
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
