<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('الملف الشخصي') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-10 bg-white shadow-2xl sm:rounded-3xl border border-brand-gold/10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-full h-1 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-10 bg-white shadow-2xl sm:rounded-3xl border border-brand-gold/10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-full h-1 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-10 bg-white shadow-2xl sm:rounded-3xl border border-red-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-full h-1 bg-red-500"></div>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
