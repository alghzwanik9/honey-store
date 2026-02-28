<x-layout title="الدفع">
    <div class="container mx-auto px-4 py-12" dir="rtl">
        <h1 class="text-3xl md:text-4xl font-black text-brand-navy mb-12 text-center">بوابة الدفع الآمنة</h1>

        <div class="max-w-md mx-auto bg-brand-cream-light rounded-3xl shadow-2xl border border-brand-gold/10 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-full h-1 bg-brand-gold"></div>
            
            @if(session('error'))
                <div class="bg-red-50 border-b border-red-100 p-4 text-red-600 text-sm font-bold text-center animate-pulse">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Order Summary -->
            <div class="bg-brand-beige/50 px-8 py-6 border-b border-brand-gold/5">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">رقم الطلب:</span>
                    <span class="font-bold">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center text-lg">
                    <span class="text-brand-navy font-bold">المبلغ المطلوب:</span>
                    <span class="text-brand-gold font-black flex items-center gap-2">
                        {{ number_format($order->total_amount, 2) }} 
                        <x-currency-symbol />
                    </span>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="p-6">
                <!-- Moyasar Form -->
                <link rel="stylesheet" href="https://cdn.moyasar.com/mpf/1.14.0/moyasar.css">
                <div class="mysr-form"></div>

                <div class="text-center mt-8">
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-brand-gold transition-colors font-black underline">إلغاء والعودة للوحة التحكم</a>
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
