<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="/icon-v4.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icon-v4.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/icon-v4.png">
        <link rel="manifest" href="/site.webmanifest?v=4">
        <link rel="mask-icon" href="/icon-v4.png" color="#d97706">
        <meta name="theme-color" content="#d97706">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-cairo text-gray-900 antialiased bg-brand-cream">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-brand-cream relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-gold/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brand-navy/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 transition-transform duration-500 hover:scale-105">
                <a href="/" class="flex flex-col items-center gap-2">
                    <img src="/images/logo.png" alt="مناحل وادي الجرفة" class="w-56 h-auto drop-shadow-[0_0_15px_rgba(194,155,64,0.3)]" style="max-width: 240px;">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-10 px-10 py-10 bg-brand-cream-light shadow-2xl overflow-hidden sm:rounded-[2.5rem] border border-brand-gold/10 relative z-10">
                <div class="absolute top-0 right-0 w-full h-1 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
                {{ $slot }}
            </div>
        </div>
        <x-chat-widget />
    </body>
</html>
