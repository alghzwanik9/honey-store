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
    
    <link rel="icon" type="image/png" sizes="32x32" href="/icon-v4.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icon-v4.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icon-v4.png">
    <link rel="manifest" href="/site.webmanifest?v=4">
    <link rel="mask-icon" href="/icon-v4.png" color="#d97706">
    <meta name="theme-color" content="#d97706">

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>
<body class="antialiased bg-brand-cream text-gray-800 font-cairo">

    {{-- Navigation Bar: scroll-aware with Alpine.js --}}
    <nav class="bg-brand-navy border-b border-brand-gold/20 shadow-lg sticky top-0 z-50 transition-all duration-300"
         x-data="{ mobileMenuOpen: false, scrolled: false }"
         x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 40 })"
         :class="{ 'py-1 shadow-xl border-brand-gold/40': scrolled }">

        <div class="container mx-auto">
            
            {{-- MOBILE LAYOUT --}}
            <div class="flex justify-between items-center px-4 py-3 md:hidden">
                 {{-- RIGHT: Hamburger + Logo --}}
                <div class="flex items-center gap-3">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white hover:text-brand-gold focus:outline-none transition-colors duration-300">
                         <div class="relative w-8 h-8 flex justify-center items-center">
                            <svg x-show="!mobileMenuOpen" class="w-8 h-8 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                            <svg x-show="mobileMenuOpen" x-cloak class="w-8 h-8 absolute" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </div>
                    </button>
                    <a href="/" class="flex items-center gap-2">
                        <img src="/images/logo.png" class="h-10 md:h-14 w-auto drop-shadow-lg object-contain" alt="مناحل وادي الجرفة">
                    </a>
                </div>

                {{-- LEFT: Cart --}}
                <a href="{{ route('cart.index') }}" class="relative group p-2 bg-brand-navy-light rounded-full hover:bg-brand-gold transition-colors">
                    <span class="text-white group-hover:text-brand-navy transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 bg-brand-gold text-brand-navy text-[10px] font-bold h-5 w-5 flex items-center justify-center rounded-full shadow-sm">{{ count(session('cart')) }}</span>
                    @endif
                </a>
            </div>

            {{-- DESKTOP LAYOUT (Single Horizontal Line) --}}
            <div class="hidden md:flex justify-between items-center py-2 px-6 lg:px-8">
                
                {{-- Left Side: Brand & Navigation --}}
                <div class="flex items-center gap-8">
                    <!-- Brand -->
                    <a href="/" class="flex items-center group shrink-0">
                        <div class="transition-transform duration-500 group-hover:scale-105">
                            <img src="/images/logo.png" class="h-10 md:h-12 w-auto object-contain" alt="مناحل وادي الجرفة">
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden lg:flex items-center gap-6 text-sm font-bold text-gray-300">
                        <a href="/" class="hover:text-brand-gold py-1 border-b-2 border-transparent hover:border-brand-gold transition-all duration-300 {{ request()->routeIs('home') ? 'text-brand-gold border-brand-gold' : '' }}">الرئيسية</a>
                        
                        <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                             <button class="flex items-center gap-1 hover:text-brand-gold py-1 border-b-2 border-transparent hover:border-brand-gold transition-all duration-300">
                                <span>المنتجات</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 top-full mt-2 w-48 bg-brand-navy-light rounded-2xl shadow-2xl z-50 overflow-hidden border border-brand-gold/20 py-1" style="display: none;">
                                <a href="/" class="block px-4 py-2.5 text-sm text-white font-bold hover:bg-brand-gold hover:text-brand-navy">الكل</a>
                                 @if(isset($honeyCategories))
                                    @foreach($honeyCategories as $category)
                                        <a href="/?category={{ $category->slug }}" class="block px-4 py-2.5 text-sm text-gray-300 hover:bg-brand-gold hover:text-brand-navy transition-colors">{{ $category->name }}</a>
                                    @endforeach
                                @endif
                                <div class="border-t border-brand-gold/10 my-1"></div>
                                <a href="/?category=offers" class="block px-4 py-2.5 text-sm text-brand-gold hover:bg-brand-gold hover:text-brand-navy transition-colors">العروض</a>
                                <a href="/?category=beeswax" class="block px-4 py-2.5 text-sm text-gray-300 hover:bg-brand-gold hover:text-brand-navy transition-colors">شمع العسل</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Actions (Search, Cart, User) --}}
                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <div class="relative group hidden xl:block">
                        <form action="{{ route('home') }}" method="GET">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث..." class="bg-brand-navy-light/50 border border-brand-gold/20 text-white placeholder-gray-400 rounded-full px-4 py-1.5 w-48 lg:w-64 focus:ring-2 focus:ring-brand-gold focus:bg-brand-navy-light focus:border-transparent transition-all shadow-sm text-sm">
                            <button type="submit" class="absolute left-2.5 top-1.5 text-gray-400 hover:text-brand-gold transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </form>
                    </div>
                    
                    <div class="h-6 w-px bg-brand-gold/20 hidden xl:block"></div>

                     <!-- Cart -->
                     <a href="{{ route('cart.index') }}" class="relative group flex items-center gap-1.5 bg-brand-gold px-4 py-1.5 rounded-full hover:bg-brand-gold-light transition-all shadow-md hover:shadow-brand-gold/20 duration-300">
                        <span class="text-brand-navy font-bold text-sm hidden sm:inline-block">السلة</span>
                        <div class="relative">
                            <svg class="w-4 h-4 text-brand-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute -top-2 -right-2 bg-brand-navy text-white text-[9px] font-bold h-4 w-4 flex items-center justify-center rounded-full border border-brand-gold">{{ count(session('cart')) }}</span>
                            @endif
                        </div>
                    </a>
                    
                    @auth
                        <!-- User Menu -->
                        <div class="relative group" x-data="{ userOpen: false }" @mouseenter="userOpen = true" @mouseleave="userOpen = false">
                            <button class="flex items-center gap-1.5 text-white bg-brand-navy-light px-3 py-1.5 rounded-full hover:bg-brand-gold hover:text-brand-navy transition border border-brand-gold/10 text-sm">
                                <div class="w-6 h-6 bg-brand-gold/20 rounded-full flex items-center justify-center text-brand-gold group-hover:text-brand-navy group-hover:bg-transparent">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <span class="truncate max-w-[80px] font-medium">{{ explode(' ', Auth::user()->name)[0] }}</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="userOpen" x-transition class="absolute left-0 mt-2 w-48 bg-brand-navy-light rounded-2xl shadow-2xl z-50 overflow-hidden border border-brand-gold/20 py-1" style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-brand-gold hover:text-brand-navy transition-colors">الملف الشخصي</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-brand-gold hover:text-brand-navy transition-colors">طلباتي</a>
                                <div class="border-t border-brand-gold/10 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 transition-colors">تسجيل الخروج</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-1 font-bold text-sm">
                            <a href="{{ route('login') }}" class="text-white hover:text-brand-gold px-2 py-1.5 rounded-lg transition">دخول</a>
                            <span class="text-brand-gold/30">|</span>
                            <a href="{{ route('register') }}" class="text-brand-gold hover:text-brand-gold-light px-2 py-1.5 rounded-lg transition border border-brand-gold/20 hover:border-brand-gold">تسجيل</a>
                        </div>
                    @endauth
                </div>
            </div>

        </div>
        
        <!-- Mobile Menu Overlay & Drawer -->
        <div x-show="mobileMenuOpen" class="fixed inset-0 bg-brand-navy/80 z-40 md:hidden backdrop-blur-sm" @click="mobileMenuOpen = false" style="display: none;"></div>
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition transform ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             class="fixed inset-y-0 right-0 w-3/4 max-w-sm bg-brand-navy shadow-2xl z-50 md:hidden overflow-y-auto" style="display: none;">
            
            <div class="p-8 bg-brand-navy-light border-b border-brand-gold/10 flex flex-col items-center text-center gap-4">
                <a href="/" class="transition-transform duration-300 active:scale-95">
                    <img src="/images/logo.png" class="h-10 md:h-14 w-auto object-contain" alt="Logo">
                </a>
                <div>
                    <h2 class="text-xl font-black text-white mb-1">مناحل وادي الجرفة</h2>
                    <p class="text-brand-gold text-xs font-bold tracking-widest uppercase">أصالة التراث</p>
                </div>
            </div>

            <div class="p-4 space-y-1">
                <a href="/" class="block px-4 py-3 rounded-xl text-gray-200 hover:bg-brand-gold hover:text-brand-navy font-bold transition">الرئيسية</a>
                 <a href="{{ route('cart.index') }}" class="px-4 py-3 rounded-xl text-gray-200 hover:bg-brand-gold hover:text-brand-navy font-bold transition flex justify-between items-center">
                    <span>السلة</span>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="bg-brand-gold text-brand-navy text-xs font-bold px-2 py-1 rounded-full">{{ count(session('cart')) }}</span>
                    @endif
                </a>

                {{-- Honey Accordion --}}
                <div x-data="{ honeyOpen: false }">
                    <button @click="honeyOpen = !honeyOpen" class="w-full flex justify-between items-center px-4 py-3 rounded-xl text-gray-200 hover:bg-brand-gold hover:text-brand-navy font-bold transition">
                        <span>أنواع العسل</span>
                        <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': honeyOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="honeyOpen" class="bg-brand-navy-light rounded-lg mx-2 space-y-1 p-2">
                        <a href="/" class="block px-4 py-2 text-sm text-gray-400 hover:text-brand-gold">عرض الكل</a>
                        @if(isset($honeyCategories))
                            @foreach($honeyCategories as $category)
                                <a href="/?category={{ $category->slug }}" class="block px-4 py-2 text-sm text-gray-400 hover:text-brand-gold">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <a href="/?category=offers" class="block px-4 py-3 rounded-xl text-gray-200 hover:bg-brand-gold hover:text-brand-navy font-bold transition">العروض</a>
                <a href="/?category=beeswax" class="block px-4 py-3 rounded-xl text-gray-200 hover:bg-brand-gold hover:text-brand-navy font-bold transition">شمع العسل</a>

                <div class="border-t border-brand-gold/10 my-4 pt-4">
                     @if (Route::has('login'))
                        @auth
                            <div class="px-4 mb-2 text-sm text-gray-500">مرحباً، {{ Auth::user()->name }}</div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 rounded-xl text-gray-200 hover:bg-brand-gold hover:text-brand-navy font-bold transition">الملف الشخصي</a>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-3 rounded-xl text-gray-200 hover:bg-brand-gold hover:text-brand-navy font-bold transition">طلباتي</a>
                            @if(Auth::user()->is_admin)
                                <a href="{{ url('/admin') }}" class="block px-4 py-3 rounded-xl text-brand-navy bg-brand-gold hover:bg-brand-gold-light font-bold transition">الإدارة</a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-right px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 font-bold transition">تسجيل الخروج</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-4 py-3 rounded-xl text-gray-200 hover:bg-brand-gold hover:text-brand-navy font-bold transition">تسجيل الدخول</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block px-4 py-3 rounded-xl text-brand-navy bg-brand-gold hover:bg-brand-gold-light font-bold transition mt-2 text-center">إنشاء حساب جديد</a>
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

    <footer class="bg-brand-navy text-gray-300 pt-16 pb-8 border-t-4 border-brand-gold mt-0" dir="rtl">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-10 text-center md:text-right mb-12">
                <div>
                    <img src="/images/logo.png" class="h-10 md:h-14 w-auto mb-4 mx-auto md:mx-0 opacity-90 hover:opacity-100 transition duration-500 drop-shadow-[0_0_10px_rgba(194,155,64,0.3)] object-contain" alt="مناحل وادي الجرفة">
                    <p class="text-sm leading-relaxed text-gray-400 font-medium">مناحل وادي الجرفة.. أصالة التراث وعذوبة المذاق في كل قطرة. نقدم لكم أجود أنواع العسل البلدي الطبيعي من مناحلنا مباشرة إليكم بكل فخر.</p>
                    {{-- Social Icons --}}
                    <div class="flex gap-4 mt-5 justify-center md:justify-start">
                        <a href="https://wa.me/966550360118" target="_blank" class="w-9 h-9 rounded-full bg-brand-navy-light hover:bg-green-600 flex items-center justify-center transition-colors duration-300 border border-brand-gold/10" title="واتساب">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <a href="https://tiktok.com/@aljorfahhoney" target="_blank" class="w-9 h-9 rounded-full bg-brand-navy-light hover:bg-black flex items-center justify-center transition-colors duration-300 border border-brand-gold/10" title="تيك توك">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.67a8.18 8.18 0 004.78 1.52V6.74a4.85 4.85 0 01-1.01-.05z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg mb-5 border-r-4 border-brand-gold pr-3">روابط سريعة</h3>
                    <ul class="space-y-3 text-sm">
                        <li><a href="/" class="hover:text-brand-gold transition flex items-center gap-2 justify-center md:justify-start"><span class="text-brand-gold">›</span> الرئيسية</a></li>
                        <li><a href="#" class="hover:text-brand-gold transition flex items-center gap-2 justify-center md:justify-start"><span class="text-brand-gold">›</span> عن مناحل وادي الجرفة</a></li>
                        <li><a href="{{ route('privacy-policy') }}" class="hover:text-brand-gold transition flex items-center gap-2 justify-center md:justify-start"><span class="text-brand-gold">›</span> سياسة الخصوصية</a></li>
                        <li><a href="{{ route('refund-policy') }}" class="hover:text-brand-gold transition flex items-center gap-2 justify-center md:justify-start"><span class="text-brand-gold">›</span> سياسة الاستبدال والاسترجاع</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-brand-gold transition flex items-center gap-2 justify-center md:justify-start"><span class="text-brand-gold">›</span> الشروط والأحكام</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg mb-5 border-r-4 border-brand-gold pr-3">تواصل معنا</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-3 justify-center md:justify-start">
                            <span class="text-brand-gold">📍</span> المملكة العربية السعودية
                        </li>
                        <li class="flex items-center gap-3 justify-center md:justify-start">
                            <span class="text-brand-gold">✉️</span> <a href="mailto:aljorfah.honey@gmail.com" class="hover:text-brand-gold transition">aljorfah.honey@gmail.com</a>
                        </li>
                        <li class="flex items-center gap-3 justify-center md:justify-start">
                            <span class="text-brand-gold">📞</span> <a href="tel:+966550360118" class="hover:text-brand-gold transition font-bold tracking-wider">0550360118</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-brand-gold/10 pt-6 text-center text-xs text-gray-500">
                <p>© {{ date('Y') }} <span class="text-brand-gold">مناحل وادي الجرفة</span> · جميع الحقوق محفوظة · صُنع بـ ❤️ في المملكة العربية السعودية</p>
            </div>
        </div>
    </footer>

    <x-chat-widget />
</body>
</html>