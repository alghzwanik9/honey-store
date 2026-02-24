<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>مرحباً بك في متجر العسل</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 text-gray-800 flex items-center justify-center min-h-screen">
        <div class="text-center p-12 bg-white rounded-3xl shadow-2xl border border-brand-gold/10 max-w-lg mx-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-full h-1.5 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
            <div class="mb-8 flex justify-center">
                 <div class="bg-brand-gold/10 p-5 rounded-full border border-brand-gold/20 shadow-inner">
                    <span class="text-7xl">🍯</span>
                 </div>
            </div>
            <h1 class="text-4xl font-black text-brand-navy mb-4 tracking-tight">مناحل وادي الجرفة</h1>
            <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                اكتشف أجود أنواع العسل الطبيعي. سدر، طلح، والمزيد من خيرات الطبيعة.
            </p>
            
            <div class="flex flex-col gap-4">
                <a href="{{ url('/') }}" class="w-full bg-brand-navy hover:bg-brand-gold text-brand-gold hover:text-brand-navy font-black py-4 px-8 rounded-xl transition-all duration-300 shadow-xl transform active:scale-[0.98]">
                    تصفح المتجر الفاخر
                </a>
                
                @if (Route::has('login'))
                    <div class="flex gap-4 justify-center mt-4 text-sm font-medium text-gray-500">
                        @auth
                            <a href="{{ url('/admin') }}" class="hover:text-brand-gold transition-colors">لوحة التحكم</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-brand-gold transition-colors">دخول المشرفين</a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
