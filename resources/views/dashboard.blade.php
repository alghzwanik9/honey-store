<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مرحباً بك') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-brand-cream-light overflow-hidden shadow-2xl sm:rounded-[3rem] border border-brand-gold/10 relative">
                <div class="absolute top-0 right-0 w-full h-2 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
                <div class="p-16 text-brand-navy text-center">
                    <div class="mb-10 flex justify-center">
                        <div class="transition-all duration-500 hover:scale-110">
                             <img src="/images/logo.png" class="h-32 w-auto object-contain" alt="Logo">
                        </div>
                    </div>
                    <h3 class="text-4xl font-black text-brand-navy mb-4">{{ __("مرحباً بك في لوحة التحكم") }}</h3>
                    <p class="text-gray-400 mb-12 text-xl font-medium">{{ __("من هنا يمكنك متابعة طلباتك وإدارة حسابك بكل فخر في مناحلنا.") }}</p>
                    
                    <div class="flex justify-center mt-6">
                        <a href="/" class="inline-flex items-center justify-center px-10 py-4 text-lg font-black text-brand-gold transition-all duration-300 transform rounded-xl hover:-translate-y-1 hover:shadow-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-gold shadow-xl bg-brand-navy hover:bg-brand-gold hover:text-brand-navy group" style="min-width: 240px; text-decoration: none;">
                            {{ __("تسوق الآن") }}
                            <svg class="w-6 h-6 mr-3 -ml-1 rtl:ml-3 rtl:-mr-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
