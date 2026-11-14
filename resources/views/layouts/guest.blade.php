<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google-site-verification" content="u6I9tBc82GeY02xZ-Bd_KYRrB7-fH1XfOp4P7YYcig4" />

        <title>{{ $title ?? 'متجر رِواء' }}</title>
        <meta name="description" content="متجر رِواء.. مصدرك الموثوق للعسل البلدي الطبيعي. جودة مضمونة 100%، ننتقيها بعناية من أجود المناحل لنجعلها بين يديك.">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <meta property="og:site_name" content="متجر رِواء">
        <script type="application/ld+json">
        {
        "@@context" : "https://schema.org",
        "@@type" : "WebSite",
          "name" : "متجر رِواء",
          "url" : "{{ url('/') }}"
        }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#d97706">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-cairo text-gray-200 antialiased bg-brand-navy">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-brand-navy relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-gold/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brand-navy/5 rounded-full blur-3xl"></div>

            <div class="relative z-10 transition-transform duration-500 hover:scale-105 mb-6">
                <a href="/" class="flex flex-col items-center gap-2">
                    <img src="/images/logo.png" alt="متجر رِواء" class="h-32 md:h-48 w-auto object-contain filter drop-shadow-[0_0_25px_rgba(194,155,64,0.5)] brightness-110">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-10 px-10 py-10 bg-brand-navy-light shadow-2xl overflow-hidden sm:rounded-[2.5rem] border border-brand-gold/10 relative z-10">
                <div class="absolute top-0 right-0 w-full h-1 bg-gradient-to-l from-brand-gold via-brand-gold-light to-brand-gold"></div>
                {{ $slot }}
            </div>
        </div>
        <x-chat-widget />
    </body>
</html>
