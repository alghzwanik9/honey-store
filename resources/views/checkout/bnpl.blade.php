<x-layout title="إتمام الدفع">
    <div class="container mx-auto px-4 py-16" dir="rtl">
        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-xl overflow-hidden text-center p-10 border border-gray-100">
            
            <div class="mb-8">
                @if($order->payment_method == 'tamara')
                    <h2 class="text-3xl font-bold text-gray-800" style="color: #c08d5d;">تمارا</h2>
                    <p class="text-gray-500 mt-2">جاري تحويلك إلى بوابة دفع تمارا...</p>
                @else
                    <h2 class="text-3xl font-bold text-green-600">tabby</h2>
                    <p class="text-gray-500 mt-2">جاري تحويلك إلى بوابة دفع تابي...</p>
                @endif
            </div>

            <div class="flex justify-center mb-8">
                <svg class="animate-spin h-12 w-12 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">رقم الطلب:</span>
                    <span class="font-bold">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">المبلغ:</span>
                    <span class="font-bold text-amber-700 flex items-center gap-3" style="gap: 1rem;">
                        {{ number_format($order->total_amount) }} 
                        <svg class="w-6 h-6 text-emerald-600 drop-shadow-sm mr-2" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                            <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                        </svg>
                    </span>
                </div>
            </div>

            <p class="text-sm text-gray-400 mb-8">هذه صفحة محاكاة (Simulation) نظراً لعدم توفر حساب تاجر حقيقي.</p>

            <form action="{{ route('checkout.callback') }}" method="GET">
                <input type="hidden" name="id" value="simulated_payment_id_{{ $order->id }}">
                <input type="hidden" name="status" value="paid">
                <input type="hidden" name="message" value="Simulated Success">
                <input type="hidden" name="order_id" value="{{ $order->id }}"> <!-- Sending Order ID explicitly -->

                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200">
                    محاكاة دفع ناجح ✅
                </button>
            </form>
            
            <div class="mt-4">
                 <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">إلغاء</a>
            </div>
        </div>
    </div>
</x-layout>
