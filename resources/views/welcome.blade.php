<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>مرحباً بك في متجر العسل</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-orange-50 text-gray-800 flex items-center justify-center min-h-screen">
        <div class="text-center p-8 bg-white rounded-2xl shadow-xl border border-amber-100 max-w-lg mx-4">
            <div class="mb-6 flex justify-center">
                 <div class="bg-amber-100 p-4 rounded-full">
                    <span class="text-6xl">🍯</span>
                 </div>
            </div>
            <h1 class="text-4xl font-bold text-amber-900 mb-4">متجر العسل الفاخر</h1>
            <p class="text-gray-600 mb-8 text-lg leading-relaxed">
                اكتشف أجود أنواع العسل الطبيعي. سدر، طلح، والمزيد من خيرات الطبيعة.
            </p>
            
            <div class="flex flex-col gap-4">
                <a href="{{ url('/') }}" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 px-6 rounded-xl transition-colors shadow-md">
                    تصفح المتجر
                </a>
                
                @if (Route::has('login'))
                    <div class="flex gap-4 justify-center mt-4 text-sm font-medium text-gray-500">
                        @auth
                            <a href="{{ url('/admin') }}" class="hover:text-amber-700">لوحة التحكم</a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-amber-700">دخول المشرفين</a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
