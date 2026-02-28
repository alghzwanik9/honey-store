<x-layout title="تفاصيل الطلب #{{ $order->id }}">
    <div class="container mx-auto px-4 py-8" dir="rtl">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-brand-navy flex items-center gap-3">
                        <span>تفاصيل الطلب #{{ $order->id }}</span>
                        <span class="text-sm px-4 py-1.5 rounded-full {{ $order->payment_status == 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-brand-gold/10 text-brand-gold border border-brand-gold/20' }}">
                            {{ $order->payment_status == 'paid' ? 'مدفوع' : 'غير مدفوع' }}
                        </span>
                    </h1>
                    <p class="text-gray-500 mt-1">تاريخ الطلب: {{ $order->created_at->format('Y-m-d h:i A') }}</p>
                </div>
                <div class="flex gap-3">
                    @if($order->payment_status == 'paid')
                        <a href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center gap-2 bg-white border-2 border-brand-gold/20 text-brand-navy px-5 py-2.5 rounded-xl hover:bg-brand-gold transition shadow-lg font-black group">
                            <svg class="w-5 h-5 text-brand-gold group-hover:text-brand-navy transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>تحميل الفاتورة</span>
                        </a>
                    @else
                        <a href="{{ route('checkout.payment', $order) }}" class="inline-flex items-center gap-2 bg-brand-navy text-brand-gold px-6 py-2.5 rounded-xl hover:bg-brand-gold hover:text-brand-navy transition shadow-xl font-black group">
                            <span>إتمام الدفع</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-brand-cream-light rounded-[2rem] shadow-2xl border border-brand-gold/10 p-10 mb-10 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-full h-1 bg-brand-gold"></div>
                <h3 class="text-xl font-black text-brand-navy mb-10 border-b border-brand-gold/5 pb-4">حالة الطلب</h3>
                
                <div class="relative px-4">
                    <div class="absolute top-1/2 left-0 w-full h-1.5 bg-brand-beige -translate-y-1/2 rounded-full z-0"></div>
                    <div class="absolute top-1/2 right-0 h-1.5 bg-gradient-to-l from-emerald-500 to-emerald-400 -translate-y-1/2 rounded-full z-0 transition-all duration-1000 shadow-[0_0_10px_rgba(16,185,129,0.3)]" 
                         style="width: {{ $order->status == 'completed' ? '100%' : ($order->status == 'shipped' ? '50%' : '0%') }}"></div>

                    <div class="relative z-10 flex justify-between w-full">
                        <!-- Step 1: Pending -->
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white shadow-sm {{ $order->status != 'cancelled' ? 'bg-green-500 text-white' : 'bg-gray-300' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <span class="text-sm font-bold {{ $order->status != 'cancelled' ? 'text-green-600' : 'text-gray-500' }}">تم استلام الطلب</span>
                        </div>

                        <!-- Step 2: Shipped -->
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white shadow-sm {{ in_array($order->status, ['shipped', 'completed']) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                            </div>
                            <span class="text-sm font-bold {{ in_array($order->status, ['shipped', 'completed']) ? 'text-green-600' : 'text-gray-400' }}">تم الشحن</span>
                        </div>

                        <!-- Step 3: Completed -->
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center border-4 border-white shadow-sm {{ $order->status == 'completed' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-bold {{ $order->status == 'completed' ? 'text-green-600' : 'text-gray-400' }}">تم التوصيل</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items & Summary -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Items List -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-brand-cream-light rounded-3xl shadow-xl border border-brand-gold/10 overflow-hidden">
                        <table class="w-full text-sm text-right">
                            <thead class="bg-brand-navy text-brand-gold font-bold uppercase transition-colors">
                                <tr>
                                    <th class="px-6 py-4">المنتج</th>
                                    <th class="px-6 py-4 text-center">الكمية</th>
                                    <th class="px-6 py-4">السعر</th>
                                    <th class="px-6 py-4">المجموع</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($order->items as $item)
                                    <tr class="hover:bg-brand-beige/30 border-b border-brand-gold/5 last:border-b-0 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $item->product->name }}
                                        </td>
                                        <td class="px-6 py-4 text-center">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-1 font-bold">
                                                {{ number_format($item->price, 2) }} 
                                                <x-currency-symbol class="text-brand-gold/70" />
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 font-black text-brand-navy text-lg">
                                            <div class="flex items-center gap-1">
                                                {{ number_format($item->price * $item->quantity) }} 
                                                <x-currency-symbol class="text-brand-gold" />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="md:col-span-1">
                    <div class="bg-brand-cream-light rounded-3xl shadow-xl p-8 border border-brand-gold/10 sticky top-24 overflow-hidden">
                        <div class="absolute top-0 right-0 w-full h-1 bg-brand-gold"></div>
                        <h3 class="text-lg font-black text-brand-navy mb-6">ملخص الطلب</h3>
                        <div class="space-y-3 text-sm border-b border-gray-100 pb-4 mb-4">
                            <div class="flex justify-between text-gray-500 font-medium">
                                <span>المجموع الفرعي</span>
                                <span class="flex items-center gap-1 text-brand-navy">
                                    {{ number_format($order->total_amount, 2) }} 
                                    <x-currency-symbol class="text-brand-gold/40" />
                                </span>
                            </div>
                            <!-- Mock Tax/Shipping if needed later -->
                            <div class="flex justify-between text-gray-600">
                                <span>الشحن</span>
                                <span>مجاني</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-8">
                            <span class="font-black text-brand-navy text-lg">الإجمالي</span>
                            <span class="font-black text-brand-gold text-3xl flex items-center gap-1">
                                {{ number_format($order->total_amount) }} 
                                <x-currency-symbol />
                            </span>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg text-xs text-gray-500 space-y-2">
                            <p><span class="font-bold">طريقة الدفع:</span> {{ $order->payment_method ?? 'بطاقة ائتمان' }}</p>
                            <p><span class="font-bold">العنوان:</span> {{ $order->address ?? 'غير محدد' }} - {{ $order->city }}</p>
                            <p><span class="font-bold">الجوال:</span> {{ $order->customer_phone }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
