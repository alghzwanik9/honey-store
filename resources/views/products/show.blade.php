<x-layout title="{{ $product->name }}">
    <div class="container mx-auto px-4 py-8" dir="rtl">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden max-w-5xl mx-auto border border-amber-50">
            <div class="flex flex-col md:flex-row-reverse h-full"> 
                
                {{-- قسم الصورة --}}
                <div class="md:w-1/2 relative bg-gray-50 h-[500px] overflow-hidden group flex items-center justify-center"> {{-- ارتفاع ثابت --}}
                     
                     {{-- Virtual Label Overlay (Bottom Center) --}}
                     <div class="absolute bottom-8 left-0 right-0 flex justify-center z-10 pointer-events-none">
                        @if($product->weight)
                            @php
                                $categoryName = $product->category ? $product->category->name : '';
                                $badgeClasses = 'bg-black/70 text-amber-400 border-amber-500/30'; // Default
                                
                                if (Str::contains($categoryName, 'مجرى')) {
                                    $badgeClasses = 'bg-white/90 text-gray-900 border-gray-200 shadow-md';
                                } elseif (Str::contains($categoryName, ['سلام', 'سمرة'])) {
                                    $badgeClasses = 'bg-[#3E2723]/90 text-amber-100 border-[#5D4037] shadow-md';
                                } elseif (Str::contains($categoryName, 'سدر')) {
                                    $badgeClasses = 'bg-[#D2691E]/90 text-white border-[#A0522D] shadow-md';
                                } elseif (Str::contains($categoryName, 'قتاد')) {
                                    $badgeClasses = 'bg-[#FFD700]/90 text-amber-900 border-[#FFC107] shadow-md';
                                } elseif (Str::contains($categoryName, 'ضهيانة')) {
                                    $badgeClasses = 'bg-[#FBC02D]/90 text-gray-900 border-[#F9A825] shadow-md';
                                } elseif (Str::contains($categoryName, 'مورينقا')) {
                                    $badgeClasses = 'bg-amber-500/20 backdrop-blur-md text-amber-900 border-amber-500 shadow-sm';
                                }
                            @endphp
                            <span class="{{ $badgeClasses }} backdrop-blur-md text-sm font-bold px-4 py-1 rounded-full border shadow-xl tracking-wider transition-colors duration-300">
                                {{ $product->weight }}
                            </span>
                        @endif
                    </div>

                     @php
                        // Image Logic: Product Image > Category Image > Placeholder
                        $imageUrl = $product->image_url ? Storage::url($product->image_url) : ($product->category && $product->category->image ? Storage::url($product->category->image) : null);
                    @endphp

                     @if($imageUrl)
                        <img src="{{ $imageUrl }}?v={{ time() }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300 flex-col gap-2">
                             <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-sm font-medium">لا توجد صورة متوفرة</span>
                        </div>
                    @endif
                </div>
                
                {{-- قسم المحتوى --}}
                <div class="md:w-1/2 p-8 md:p-12 flex flex-col items-start text-right">
                    <div class="w-full">
                        @if($product->category)
                            <a href="#" class="text-amber-600 font-bold text-sm uppercase tracking-wide mb-2 inline-block hover:underline">
                                {{ $product->category->name }}
                            </a>
                        @endif
                        <h1 class="text-4xl font-black text-gray-900 mb-6 leading-tight">{{ $product->name }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 mb-8 bg-gray-50 p-4 rounded-xl w-full">
                            <div class="flex items-center gap-2 pl-4 border-l border-gray-200">
                                <span class="text-3xl font-black text-amber-700">{{ number_format($product->price) }}</span>
                                <svg class="w-8 h-8 text-black" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                    <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                </svg>
                            </div>
                            
                            @if($product->weight)
                            <div class="flex items-center gap-2 text-gray-700 bg-white px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                                <span class="font-bold text-sm">{{ $product->weight }}</span>
                            </div>
                            @endif

                            @if($product->stock > 0)
                                <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold mr-auto">متوفر</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold mr-auto">نفذت الكمية</span>
                            @endif
                        </div>
                    </div>

                    <div class="prose prose-lg text-gray-600 mb-8 leading-relaxed max-w-none">
                        {!! $product->description !!}
                    </div>

                    <div class="mt-auto w-full pt-6"> {{-- إضافة padding-top --}}
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center gap-4 w-full">
                            @csrf
                            <div class="w-32 shrink-0"> {{-- shrink-0 لضمان عدم الانكماش --}}
                                <label for="quantity" class="sr-only">الكمية</label>
                                <input type="number" id="quantity" name="quantity" min="1" max="{{ $product->stock }}" value="1" class="w-full border-2 border-gray-300 rounded-xl focus:ring-amber-500 focus:border-amber-500 text-center py-3 font-bold text-lg text-gray-900">
                            </div>
                            <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>أضف للسلة</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>