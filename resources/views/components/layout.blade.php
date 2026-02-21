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
    
    <link rel="icon" href="/favicon.png" type="image/png">
    <link rel="apple-touch-icon" href="/favicon.png">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-slate-50 text-gray-800 font-cairo">

    {{-- Navigation Bar: scroll-aware with Alpine.js --}}
    <nav class="bg-white border-b border-amber-100 shadow-sm sticky top-0 z-50 transition-all duration-300"
         x-data="{ mobileMenuOpen: false, scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 40 })"
         :class="{ 'nav-scrolled': scrolled }">

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
                        <img src="/images/logo.png" alt="مناحل وادي الجرفة" class="h-12 w-auto object-contain drop-shadow-md">
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
                    <a href="/" class="flex items-center gap-2 group">
                        <img src="/images/logo.png" alt="مناحل وادي الجرفة" class="h-20 w-auto object-contain group-hover:scale-105 transition-transform duration-300 drop-shadow-md">
                        <span class="text-sm font-medium text-amber-600">أصالة العسل البلدي</span>
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

    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8 border-t-4 border-amber-500 mt-0" dir="rtl">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-10 text-center md:text-right mb-12">
                <div>
                    <img src="/images/logo.png" alt="Logo" class="h-16 w-auto mb-4 mx-auto md:mx-0 opacity-80 hover:opacity-100 transition duration-500">
                    <p class="text-sm leading-relaxed text-gray-400">نقدم لكم أجود أنواع العسل البلدي الطبيعي من مناحلنا مباشرة إليكم. جودة مضمونة وطعم لا يُنسى.</p>
                    {{-- Social Icons --}}
                    <div class="flex gap-4 mt-5 justify-center md:justify-start">
                        <a href="#" class="w-9 h-9 rounded-full bg-gray-800 hover:bg-amber-600 flex items-center justify-center transition-colors duration-300" title="تويتر">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.742l7.742-8.855L1.5 2.25h6.993l4.259 5.632zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full bg-gray-800 hover:bg-amber-600 flex items-center justify-center transition-colors duration-300" title="انستقرام">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full bg-gray-800 hover:bg-green-500 flex items-center justify-center transition-colors duration-300" title="واتساب">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full bg-gray-800 hover:bg-black flex items-center justify-center transition-colors duration-300" title="تيك توك">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.67a8.18 8.18 0 004.78 1.52V6.74a4.85 4.85 0 01-1.01-.05z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg mb-5">روابط سريعة</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="/" class="hover:text-amber-400 transition flex items-center gap-2 justify-center md:justify-start"><span class="text-amber-600">›</span> الرئيسية</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition flex items-center gap-2 justify-center md:justify-start"><span class="text-amber-600">›</span> عن المناحل</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-amber-400 transition flex items-center gap-2 justify-center md:justify-start"><span class="text-amber-600">›</span> السلة</a></li>
                        <li><a href="#" class="hover:text-amber-400 transition flex items-center gap-2 justify-center md:justify-start"><span class="text-amber-600">›</span> الشروط والأحكام</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg mb-5">تواصل معنا</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-2 justify-center md:justify-start">
                            <span class="text-amber-500">📍</span> المملكة العربية السعودية
                        </li>
                        <li class="flex items-center gap-2 justify-center md:justify-start">
                            <span class="text-amber-500">✉️</span> aljorfah.honey@gmail.com
                        </li>
                        <li class="flex items-center gap-2 justify-center md:justify-start">
                            <span class="text-amber-500">📞</span> <a href="tel:+966550360118" class="hover:text-amber-400 transition">0550360118</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 text-center text-xs text-gray-500">
                <p>© {{ date('Y') }} مناحل وادي الجرفة · جميع الحقوق محفوظة · صُنع بـ ❤️ في المملكة العربية السعودية</p>
            </div>
        </div>
    </footer>

    <x-chat-widget />
</body>
</html>