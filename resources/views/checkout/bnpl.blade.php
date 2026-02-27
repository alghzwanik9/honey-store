<x-layout title="إتمام الدفع">
    <div class="container mx-auto px-4 py-20" dir="rtl">
        <div class="max-w-md mx-auto bg-brand-cream-light rounded-3xl shadow-2xl overflow-hidden text-center p-12 border border-brand-gold/10 relative">
            <div class="absolute top-0 right-0 w-full h-1.5 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
            
            <div class="mb-10">
                <div class="flex justify-center mb-4">
                    <div class="bg-[#c08d5d]/10 px-6 py-2 rounded-xl border border-[#c08d5d]/20">
                        <h2 class="text-3xl font-black" style="color: #c08d5d;">TAMARA</h2>
                    </div>
                </div>
                <p id="status-text" class="text-gray-400 font-black">جاري تحويلك إلى بوابة دفع تمارا الآمنة...</p>
            </div>

            <div id="loader" class="flex justify-center mb-8">
                <svg class="animate-spin h-12 w-12 text-brand-gold" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <div id="error-container" class="hidden bg-red-50 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100">
                <p id="error-message"></p>
                <div class="mt-4 flex gap-2 justify-center">
                     <a href="{{ route('checkout.payment', $order) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-xs">إعادة المحاولة</a>
                     <a href="{{ route('dashboard') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-xs">العودة للوحة التحكم</a>
                </div>
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

            <p class="text-sm text-gray-400 mb-4 font-medium">سيتم توجيهك الآن لإكمال عملية الدفع عبر بوابة شركائنا الرسمية.</p>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusText = document.getElementById('status-text');
            const errorContainer = document.getElementById('error-container');
            const errorMessage = document.getElementById('error-message');
            const loader = document.getElementById('loader');

            // Trigger Tamara Initialization via AJAX
            fetch('{{ route('tamara.init', $order) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(res => {
                if (res.status === 200 && res.body.checkout_url) {
                    statusText.innerText = 'تم التجهيز، جاري التوجيه...';
                    window.location.href = res.body.checkout_url;
                } else {
                    throw new Error(res.body.error || 'فشل الاتصال بتمارا، يرجى المحاولة لاحقاً.');
                }
            })
            .catch(error => {
                console.error('Tamara Error:', error);
                loader.classList.add('hidden');
                statusText.classList.add('hidden');
                errorContainer.classList.remove('hidden');
                errorMessage.innerText = error.message;
            });
        });
    </script>
</x-layout>
