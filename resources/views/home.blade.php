<x-layout title="الرئيسية">
    <div class="container mx-auto px-4 py-10" dir="rtl">
        @if(!request('category') && !request('search'))
        <h1 class="text-3xl font-bold text-amber-900 text-center mb-10">
            تشكيلة عسلنا الفاخرة
        </h1>
        @endif        
        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
            @foreach($products as $product)
                {{-- البطاقة: تصميم أنظف مع ظل ناعم --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group">
                    <a href="{{ route('products.show', $product) }}" class="flex flex-col grow">
                        {{-- حاوية الصورة --}}
                        <div class="h-48 md:h-64 w-full bg-gray-50 relative overflow-hidden group-hover:shadow-inner">
                            
                            {{-- Virtual Label Overlay (Bottom Center) --}}
                            <div class="absolute bottom-6 left-0 right-0 flex justify-center z-10 p-2 pointer-events-none">
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
                                    <span class="{{ $badgeClasses }} text-[10px] font-bold px-3 py-0.5 rounded-full border shadow-lg tracking-wide transition-colors duration-300">
                                        {{ $product->weight }}
                                    </span>
                                @endif
                            </div>

                            @php
                                // Image Logic: Product Image > Category Image > Placeholder
                                $imageUrl = $product->image_url ? Storage::url($product->image_url) : ($product->category && $product->category->image ? Storage::url($product->category->image) : null);
                            @endphp

                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-5 text-center flex flex-col items-center justify-between grow">
                            <div class="w-full">
                                @if($product->category)
                                    <span class="text-[10px] font-bold tracking-wider text-amber-600 bg-amber-50/50 px-2 py-1 rounded-full mb-2 inline-block">
                                        {{ $product->category->name }}
                                    </span>
                                @endif
                                <h2 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-amber-600 transition-colors line-clamp-2">
                                    {{ $product->name }}
                                </h2>
                            </div>
                            
                            <div class="flex items-center justify-center gap-2 mt-3 group-hover:text-amber-800 transition-colors">
                                <span class="text-gray-900 font-extrabold text-2xl font-sans">{{ number_format($product->price) }}</span>
                                <svg class="w-7 h-7 text-black" viewBox="0 0 1125 1257" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                    <path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                    {{-- زر الإضافة للسلة: لون أسود فخم --}}
                    <div class="p-4 pt-0 mt-auto">
                        <form action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3 px-4 rounded-xl hover:bg-amber-600 transition-all duration-300 flex items-center justify-center gap-2 group/btn shadow-md hover:shadow-lg">
                                <span>أضف للسلة</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover/btn:scale-110 transition-transform text-amber-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </div>
</x-layout>