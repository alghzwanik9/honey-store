<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('طلباتي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-brand-gold/10">
                <div class="p-6 text-gray-900" dir="rtl">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-right text-gray-500 overflow-hidden rounded-xl">
                                <thead class="text-xs text-brand-gold uppercase bg-brand-navy">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 rounded-r-lg">رقم الطلب</th>
                                        <th scope="col" class="px-6 py-3">التاريخ</th>
                                        <th scope="col" class="px-6 py-3">الإجمالي</th>
                                        <th scope="col" class="px-6 py-3">حالة الدفع</th>
                                        <th scope="col" class="px-6 py-3">حالة الطلب</th>
                                        <th scope="col" class="px-6 py-3 rounded-l-lg">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                #{{ $order->id }}
                                            </th>
                                            <td class="px-6 py-4">
                                                {{ $order->created_at->format('Y-m-d') }}
                                            </td>
                                            <td class="px-6 py-4 font-black text-brand-navy">
                                            <span class="flex items-center gap-3" style="gap: 0.75rem;">
                                                {{ number_format($order->total_amount) }} 
                                                <svg class="w-5 h-5 text-brand-gold drop-shadow-sm mr-2" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                                    <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                                </svg>
                                            </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($order->payment_status == 'paid')
                                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded border border-green-400">مدفوع</span>
                                                @else
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded border border-yellow-300">غير مدفوع</span>
                                                @endif
                                            </td>
                                             <td class="px-6 py-4">
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded border border-gray-500">
                                                    {{ $order->status == 'pending' ? 'قيد التجهيز' : ($order->status == 'shipped' ? 'تم الشحن' : $order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 flex gap-3 text-center">
                                                @if($order->payment_status != 'paid')
                                                    <a href="{{ route('checkout.payment', $order) }}" class="font-medium text-blue-600 hover:underline">إتمام الدفع</a>
                                                @else
                                                    <div class="flex flex-col gap-2">
                                                        <a href="{{ route('orders.show', $order) }}" class="font-medium text-green-600 hover:underline">عرض التفاصيل</a>
                                                        
                                                        <div class="flex gap-2 text-xs">
                                                            <a href="{{ route('orders.invoice', $order) }}" class="flex items-center gap-1 text-brand-gold hover:text-brand-navy border border-brand-gold/30 hover:bg-brand-gold px-2 py-1 rounded transition-all" title="تحميل الفاتورة">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                                تحميل
                                                            </a>
                                                            
                                                            <form action="{{ route('orders.email_invoice', $order) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="flex items-center gap-1 text-blue-600 hover:text-blue-800 border border-blue-200 px-2 py-1 rounded bg-blue-50" title="إرسال للبريد">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                                    إرسال
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500 mb-4">لم تقم بأي طلبات حتى الآن.</p>
                            <a href="/" class="text-brand-gold hover:text-brand-gold-dark font-black transition-all">تصفح المنتجات وأطلب الآن</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
