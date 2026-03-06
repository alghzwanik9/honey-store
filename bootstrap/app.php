<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. الوثوق في البروكسي (حل مشكلة HTTPS وعلامة الاستفهام ?)
        $middleware->trustProxies(at: '*');

        // 2. توجيه الزوار غير المسجلين لصفحة الدخول
$middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
    // إذا كان الرابط المطلوب يبدأ بـ admin يوجهه لدخول الإدارة، غير كذا يوجهه لصفحة العملاء
    return $request->is('admin*') ? '/admin/login' : '/login';
});
        $middleware->alias([
            'admin.redirect' => \App\Http\Middleware\AdminRedirect::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'checkout/webhook', // Exclude Moyasar Webhook
            'checkout/callback', // Exclude Callback in case of POST
            'tamara/notification', // Exclude Tamara Webhooks
            'admin/*', // Allow Filament admin POST requests
            'livewire/*', // Allow Livewire requests used by Filament
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();