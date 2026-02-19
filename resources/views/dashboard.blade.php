<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('مرحباً بك') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-amber-100">
                <div class="p-8 text-gray-900 text-center">
                    <div class="mb-6 flex justify-center">
                        <div class="bg-white p-2 rounded-2xl shadow-md inline-block">
                             <img src="/images/logo.png" alt="مناحل وادي الجرفة" class="h-32 w-auto object-contain mx-auto" style="height: 8rem; max-width: 100%;">
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-amber-900 mb-3">{{ __("شكراً لتسجيلك في مناحل وادي الجرفة") }}</h3>
                    <p class="text-amber-800/80 mb-8 text-lg font-medium">{{ __("تم إنشاء حسابك بنجاح. يمكنك الآن الاستمتاع بتجربة تسوق فريدة لأجود أنواع العسل.") }}</p>
                    
                    <div class="flex justify-center mt-6">
                        <a href="/" class="inline-flex items-center justify-center px-8 py-3 text-base font-bold text-white transition-all duration-200 transform rounded-lg hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-600 shadow-md" style="background-color: #b45309; min-width: 200px; text-decoration: none;">
                            {{ __("تسوق الآن") }}
                            <svg class="w-5 h-5 mr-2 -ml-1 rtl:ml-2 rtl:-mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
