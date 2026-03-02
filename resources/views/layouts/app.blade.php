<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google-site-verification" content="u6I9tBc82GeY02xZ-Bd_KYRrB7-fH1XfOp4P7YYcig4" />
        
        <title>{{ $title ?? 'مناحل وادي الجرفة' }}</title>
        <meta name="description" content="مناحل وادي الجرفة.. مصدرك الموثوق للعسل البلدي الطبيعي. جودة مضمونة 100%، نقطفها بعناية من مناحلنا لنجعلها بين يديك.">
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <meta property="og:site_name" content="مناحل وادي الجرفة">
        <script type="application/ld+json">
        {
          "@@context" : "https://schema.org",
          "@@type" : "WebSite",
          "name" : "مناحل وادي الجرفة",
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
    <body class="font-cairo antialiased bg-brand-cream text-gray-800">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-brand-cream-light border-b border-brand-gold/10 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-full h-0.5 bg-brand-gold"></div>
                    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <x-chat-widget />
    </body>
</html>
