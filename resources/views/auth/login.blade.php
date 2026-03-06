<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('البريد الإلكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="rounded-lg border-gray-200 text-brand-gold shadow-sm focus:ring-brand-gold transition-all duration-300"
                    name="remember">
                <span
                    class="ms-2 text-sm text-gray-400 group-hover:text-brand-navy transition-colors font-medium">{{ __('تذكرني') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-6 gap-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-400 hover:text-brand-gold transition-colors font-medium underline"
                    href="{{ route('password.request') }}">
                    {{ __('نسيت كلمة المرور؟') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('تسجيل الدخول') }}
            </x-primary-button>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-700/30 text-center text-sm">
            <span class="text-gray-400">{{ __('ليس لديك حساب؟') }}</span>
            <a class="font-bold text-brand-gold hover:text-yellow-400 transition-colors mr-2"
                href="{{ route('register') }}">
                {{ __('إنشاء حساب جديد') }}
            </a>
        </div>
    </form>
</x-guest-layout>
