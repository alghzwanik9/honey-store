<x-layout title="الدفع">
    <div class="container mx-auto px-4 py-12" dir="rtl">
        <h1 class="text-3xl md:text-4xl font-black text-brand-navy mb-12 text-center">بوابة الدفع الآمنة</h1>

        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-2xl border border-brand-gold/10 overflow-hidden">
            <!-- Order Summary -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">رقم الطلب:</span>
                    <span class="font-bold">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center text-lg">
                    <span class="text-brand-navy font-bold">المبلغ المطلوب:</span>
                    <span class="text-brand-gold font-black flex items-center gap-3" style="gap: 1rem;">
                        {{ number_format($order->total_amount) }} 
                        <svg class="w-6 h-6 text-brand-gold drop-shadow-sm mr-2" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                            <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="p-6">
                <!-- Moyasar Form -->
                <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.14.0/moyasar.css">
                <div class="mysr-form"></div>

                <div class="text-center mt-6">
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-brand-gold transition-colors font-medium underline">إلغاء والعودة للوحة التحكم</a>
                </div>
            </div>
        </div>
        
        <div class="max-w-md mx-auto mt-8 flex justify-center gap-4 grayscale opacity-50">
             <span class="text-xs text-center w-full text-gray-400">مدعوم بواسطة ميسر للدفع الإلكتروني</span>
        </div>
    </div>

    <!-- Moyasar Scripts -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=fetch"></script>
    <script src="https://cdn.moyasar.com/mpf/1.14.0/moyasar.js"></script>

    @if(str_contains($moyasarPublishableKey, 'pk_test_') && !str_contains($moyasarPublishableKey, '...'))
    <script>
      Moyasar.init({
        element: '.mysr-form',
        amount: {{ $order->total_amount * 100 }}, // Amount in Halalas
        currency: 'SAR',
        description: 'Order #{{ $order->id }}',
        publishable_api_key: '{{ $moyasarPublishableKey }}',
        callback_url: '{{ route('checkout.callback', ['order_id' => $order->id]) }}',
        methods: ['creditcard']
      })
    </script>
    @else
        <div class="bg-red-50 text-red-700 p-4 rounded-lg text-center mx-4 mb-4">
            <p class="font-bold">⚠️ تنبيه إعدادات الدفع</p>
            <p class="text-sm mt-1">يرجى إضافة مفاتيح Moyasar API الصحيحة في ملف .env لتفعيل نموذج الدفع.</p>
            <p class="text-xs mt-2 text-gray-500 font-mono">{{ $moyasarPublishableKey ?: 'No Key Found' }}</p>
        </div>
    @endif
</x-layout>
