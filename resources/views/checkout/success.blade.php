<x-layout title="تم الطلب بنجاح">
    <div class="container mx-auto px-4 py-16" dir="rtl">
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden text-center p-10 border border-amber-100">
            <div class="mb-6 flex flex-col items-center justify-center text-green-500">
                <img src="/images/logo.png" alt="مناحل وادي الجرفة" class="w-48 h-auto mb-6 drop-shadow-md" style="max-width: 200px;">
            </div>

            <h1 class="text-4xl font-extrabold text-amber-800 mb-4">تهانينا! تم الطلب بنجاح</h1>
            <p class="text-xl text-gray-600 mb-8">نشكرك على ثقتك في مناحل وادي الجرفة. طلبك قيد التجهيز الآن.</p>

            <div class="bg-amber-50 rounded-xl p-6 mb-8 text-right border border-amber-100 shadow-sm">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">رقم الطلب:</span>
                    <span class="font-bold text-gray-900 text-lg">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">حالة الدفع:</span>
                    <span class="font-bold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                        {{ $order->payment_status === 'paid' ? 'مدفوع بنجاح' : 'بانتظار الدفع / عند الاستلام' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">الإجمالي:</span>
                    <span class="font-bold text-amber-700 text-xl flex items-center gap-3" style="gap: 1rem;">
                        {{ number_format($order->total_amount, 2) }} 
                        <svg class="w-8 h-8 text-emerald-600 drop-shadow-sm mr-4" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                            <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                <a href="/" class="inline-block w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200">
                    العودة للرئيسية
                </a>
                <a href="{{ route('orders.index') }}" class="mt-4 inline-block w-full sm:w-auto bg-white border border-gray-300 text-gray-700 font-bold py-3 px-8 rounded-lg hover:bg-gray-50 transition duration-200">
                   عرض طلباتي
                </a>
                
                @if($order->payment_status === 'paid')
                <div class="mt-6 border-t border-gray-100 pt-6">
                    <a href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center gap-2 bg-green-50 text-green-700 border border-green-200 font-bold py-3 px-8 rounded-lg hover:bg-green-100 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        تحميل الفاتورة PDF
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-layout>
