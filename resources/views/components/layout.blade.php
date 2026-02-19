<!DOCTYPE html>
{{-- تعديل 1: أضفنا dir="rtl" عشان ينقلب الموقع يمين --}}
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'متجر العسل' }}</title>
    
    {{-- Google Fonts: Cairo --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <link rel="icon" href="/images/logo.png" type="image/png">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-slate-50 text-gray-800 font-cairo">

    {{-- Navigation Bar: Changed to White with Gold Border for Premium feel --}}
    <nav class="bg-white border-b border-amber-100 shadow-sm sticky top-0 z-50 transition-all duration-300" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto">
            
            {{-- MOBILE LAYOUT --}}
            <div class="flex justify-between items-center px-4 py-3 md:hidden">
                 {{-- RIGHT: Hamburger + Logo --}}
                <div class="flex items-center gap-3">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-amber-600 focus:outline-none transition-colors duration-300">
                         <div class="relative w-8 h-8 flex justify-center items-center">
                            <svg x-show="!mobileMenuOpen" class="w-8 h-8 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            <svg x-show="mobileMenuOpen" x-cloak class="w-8 h-8 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                    </button>
                    <a href="/" class="flex items-center">
                        <img src="/images/logo.png" alt="مناحل وادي الجرفة" class="h-10 w-auto object-contain">
                    </a>
                </div>

                {{-- LEFT: Cart --}}
                <a href="{{ route('cart.index') }}" class="relative group p-2 bg-gray-50 rounded-full hover:bg-amber-50 transition-colors">
                    <span class="text-gray-700 group-hover:text-amber-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold h-5 w-5 flex items-center justify-center rounded-full shadow-sm">{{ count(session('cart')) }}</span>
                    @endif
                </a>
            </div>

            {{-- DESKTOP LAYOUT --}}
            <div class="hidden md:flex flex-col gap-4 py-4 px-6 lg:px-8">
                
                {{-- Row 1: Brand & Actions --}}
                <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                    <!-- Brand (Right) -->
                    <a href="/" class="flex items-center gap-4 group">
                         <div class="p-1">
                            <img src="/images/logo.png" alt="مناحل وادي الجرفة" class="h-16 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="flex flex-col">
                            <span class="text-2xl font-black text-gray-900 leading-none group-hover:text-amber-600 transition-colors">مناحل وادي الجرفة</span>
                            <span class="text-sm font-medium text-amber-600 mt-1">أصالة العسل البلدي</span>
                        </div>
                    </a>

                    <!-- Actions -->
                    <div class="flex items-center gap-6">
                        <!-- Search: Clean Gray Input -->
                        <div class="relative group">
                            <form action="{{ route('home') }}" method="GET">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن منتج..." class="bg-gray-100 border border-transparent text-gray-700 placeholder-gray-400 rounded-full px-5 py-2.5 w-72 focus:ring-2 focus:ring-amber-500 focus:bg-white focus:border-transparent transition-all shadow-sm group-hover:bg-white group-hover:shadow-md">
                                <button type="submit" class="absolute left-3 top-2.5 text-gray-400 hover:text-amber-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </button>
                            </form>
                        </div>
                        
                        <div class="h-8 w-px bg-gray-200"></div>

                         <!-- Cart: Premium button shape preserved -->
                         <a href="{{ route('cart.index') }}" class="relative group flex items-center gap-2 bg-gray-900 px-5 py-2.5 rounded-full hover:bg-amber-600 transition-colors shadow-md hover:shadow-lg duration-300">
                            <span class="text-white font-bold group-hover:text-white">السلة</span>
                            <div class="relative">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="absolute -top-3 -right-3 bg-red-600 text-white text-[10px] font-bold h-5 w-5 flex items-center justify-center rounded-full border-2 border-white">{{ count(session('cart')) }}</span>
                                @endif
                            </div>
                        </a>
                        
                        @auth
                            <!-- User Menu -->
                            <div class="relative group" x-data="{ userOpen: false }" @mouseenter="userOpen = true" @mouseleave="userOpen = false">
                                <button class="flex items-center gap-2 text-gray-700 font-bold bg-gray-50 px-4 py-2 rounded-full hover:bg-gray-100 hover:text-amber-600 transition border border-gray-100">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="userOpen" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-2xl shadow-xl z-50 overflow-hidden border border-gray-100 py-1" style="display: none;">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">الملف الشخصي</a>
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">طلباتي</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-right px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">تسجيل الخروج</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-1 font-bold text-sm">
                                <a href="{{ route('login') }}" class="text-gray-600 hover:text-amber-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition">دخول</a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('register') }}" class="text-amber-600 hover:text-amber-700 px-3 py-2 rounded-lg hover:bg-amber-50 transition">تسجيل</a>
                            </div>
                        @endauth
                    </div>
                </div>

                {{-- Row 2: Navigation Links --}}
                <div class="flex justify-center items-center gap-10 text-base font-bold text-gray-600">
                    <a href="/" class="hover:text-amber-600 py-2 border-b-2 border-transparent hover:border-amber-500 transition-all duration-300 {{ request()->routeIs('home') ? 'text-amber-600 border-amber-500' : '' }}">الرئيسية</a>
                    
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                         <button class="flex items-center gap-1 hover:text-amber-600 py-2 border-b-2 border-transparent hover:border-amber-500 transition-all duration-300">
                            <span>جميع أنواع العسل</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute right-0 top-full mt-0 w-64 bg-white rounded-2xl shadow-xl z-50 overflow-hidden border border-gray-100 py-2" style="display: none;">
                            <a href="/" class="block px-5 py-3 text-gray-800 font-bold hover:bg-amber-50 hover:text-amber-600">عرض الكل</a>
                             @if(isset($honeyCategories))
                                @foreach($honeyCategories as $category)
                                    <a href="/?category={{ $category->slug }}" class="block px-5 py-3 text-gray-600 hover:bg-amber-50 hover:text-amber-600 transition-colors">{{ $category->name }}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <a href="/?category=offers" class="hover:text-amber-600 py-2 border-b-2 border-transparent hover:border-amber-500 transition-all duration-300">العروض</a>
                    <a href="/?category=beeswax" class="hover:text-amber-600 py-2 border-b-2 border-transparent hover:border-amber-500 transition-all duration-300">شمع العسل</a>
                </div>
            </div>

        </div>
        
        <!-- Mobile Menu Overlay & Drawer (Moved Inside Nav for Scope) -->
        <div x-show="mobileMenuOpen" class="fixed inset-0 bg-black/60 z-40 md:hidden backdrop-blur-sm" @click="mobileMenuOpen = false" style="display: none;"></div>
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition transform ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             class="fixed inset-y-0 right-0 w-3/4 max-w-sm bg-white shadow-2xl z-50 md:hidden overflow-y-auto" style="display: none;">
            
            <div class="p-6 bg-white border-b border-amber-100 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-black text-gray-900 mb-1">القائمة</h2>
                <p class="text-amber-600 text-sm font-bold">مناحل وادي الجرفة</p>
            </div>
            <div class="">
                <img src="/images/logo.png" class="h-12 w-auto" alt="Logo">
            </div>
        </div>

            <div class="p-4 space-y-1">
                <a href="/" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition">الرئيسية</a>
                {{-- Rest of mobile links with updated hover styles... --}}
                 <a href="{{ route('cart.index') }}" class="px-4 py-3 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition flex justify-between items-center">
                    <span>السلة</span>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="bg-amber-600 text-white text-xs font-bold px-2 py-1 rounded-full">{{ count(session('cart')) }}</span>
                    @endif
                </a>

                    {{-- Honey Accordion --}}
                    <div x-data="{ honeyOpen: false }">
                        <button @click="honeyOpen = !honeyOpen" class="w-full flex justify-between items-center px-4 py-3 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition">
                            <span>أنواع العسل</span>
                            <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': honeyOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="honeyOpen" class="bg-gray-50 rounded-lg mx-2 space-y-1 p-2">
                            <a href="/" class="block px-4 py-2 text-sm text-gray-600 hover:text-amber-600">عرض الكل</a>
                            @if(isset($honeyCategories))
                                @foreach($honeyCategories as $category)
                                    <a href="/?category={{ $category->slug }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-amber-600">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <a href="/?category=offers" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition">العروض</a>
                    <a href="/?category=beeswax" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition">شمع العسل</a>

                    <div class="border-t border-gray-100 my-4 pt-4">
                         @if (Route::has('login'))
                            @auth
                                <div class="px-4 mb-2 text-sm text-gray-500">مرحباً، {{ Auth::user()->name }}</div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition">الملف الشخصي</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition">طلباتي</a>
                                @if(Auth::user()->is_admin)
                                    <a href="{{ url('/admin') }}" class="block px-4 py-3 rounded-xl text-amber-700 bg-amber-50 hover:bg-amber-100 font-bold transition">الإدارة</a>
                                @endif
                                
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-right px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 font-bold transition">تسجيل الخروج</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-amber-50 hover:text-amber-700 font-bold transition">تسجيل الدخول</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="block px-4 py-3 rounded-xl text-amber-700 bg-amber-50 hover:bg-amber-100 font-bold transition mt-2 text-center">إنشاء حساب جديد</a>
                                @endif
                            @endauth
                        @endif
                    </div>
            </div>
        </div>
    </nav>

    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <footer class="bg-gray-900 text-gray-300 py-12 border-t border-gray-800 mt-20">
        <div class="container mx-auto px-6 grid md:grid-cols-3 gap-8 text-center md:text-right">
             <div>
                <img src="/images/logo.png" alt="Logo" class="h-16 w-auto mb-4 mx-auto md:mx-0 opacity-80 grayscale hover:grayscale-0 transition duration-500">
                <p class="text-sm leading-relaxed">نقدم لكم أجود أنواع العسل البلدي الطبيعي من مناحلنا مباشرة إليكم. جودة مضمونة وطعم لا يُنسى.</p>
             </div>
             <div>
                <h3 class="text-white font-bold text-lg mb-4">روابط سريعة</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="/" class="hover:text-amber-500 transition">الرئيسية</a></li>
                    <li><a href="#" class="hover:text-amber-500 transition">عن المناحل</a></li>
                    <li><a href="#" class="hover:text-amber-500 transition">الشروط والأحكام</a></li>
                </ul>
             </div>
             <div>
                <h3 class="text-white font-bold text-lg mb-4">تواصل معنا</h3>
                <p class="text-sm mb-2">الرياض، المملكة العربية السعودية</p>
                <p class="text-sm">info@honey-store.com</p>
             </div>
        </div>
        <div class="container mx-auto text-center mt-12 pt-8 border-t border-gray-800 text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} مناحل وادي الجرفة. جميع الحقوق محفوظة.</p>
        </div>
    </footer>
    <x-chat-widget />
</body>
</html>