<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// --- Public Routes ---

Route::get('/', function () {
    // If there is a search or category filter, use pagination
    if (request('search') || request('category')) {
        $query = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0);

        if (request('search')) {
            // بحث ذكي: نكسّر الجملة لكلمات، ونحذف "ال" التعريف من كل كلمة
            $rawTerms = preg_split('/\s+/', trim(request('search')), -1, PREG_SPLIT_NO_EMPTY);
            $terms = array_map(fn($t) => preg_replace('/^ال/', '', $t), $rawTerms);
            $terms = array_filter($terms); // نزيل الكلمات الفارغة

            foreach ($terms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', '%' . $term . '%')
                      ->orWhere('description', 'like', '%' . $term . '%')
                      ->orWhereHas('category', fn($c) => $c->where('name', 'like', '%' . $term . '%'));
                });
            }
        }

        if (request('category')) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', request('category'));
            });
        }

        $products = $query->paginate(12);

        return view('home', [
            'products' => $products,
            'isFiltered' => true
        ]);
    }

    // Default Home Page view - Grouped by Category
    $categoriesWithProducts = \App\Models\Category::where('slug', '!=', 'honey')
        ->with(['products' => function($q) {
            $q->where('is_active', true)->where('stock', '>', 0);
        }])->get()->filter(function ($category) {
            return $category->products->count() > 0;
        });

    $newProductsActive = \App\Models\Setting::where('key', 'new_products_active')->value('value') == '1';
    $newProducts = collect();
    if ($newProductsActive) {
        $count = \App\Models\Setting::where('key', 'new_products_count')->value('value') ?: 4;
        $newProducts = Product::with('category')->where('is_active', true)->where('stock', '>', 0)->orderBy('created_at', 'desc')->take((int)$count)->get();
    }

    return view('home', [
        'isFiltered' => false,
        'categoriesWithProducts' => $categoriesWithProducts,
        'newProductsActive' => $newProductsActive,
        'newProducts' => $newProducts
    ]);
})->name('home');

Route::get('/products/{product:slug}', function (Product $product) {
    return view('products.show', ['product' => $product]);
})->name('products.show');

// AI Chat Route (Public)
Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');

// Sitemap
Route::get('/sitemap.xml', function () {
    $products = Product::where('is_active', true)->get();
    
    $content = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . view('sitemap', [
        'products' => $products
    ])->render();

    return response($content)->header('Content-Type', 'text/xml');
});

// --- Cart Routes ---
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

// --- Payment Routes (Public to handle callbacks/redirects) ---
Route::get('/checkout/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])->name('checkout.callback');
Route::get('/checkout/{order}/success', [\App\Http\Controllers\PaymentController::class, 'success'])->name('checkout.success');
Route::get('/payment/success/{order}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');

// Tamara Callbacks
Route::get('/tamara/{order}/success', [\App\Http\Controllers\PaymentController::class, 'tamaraSuccess'])->name('tamara.success');
Route::get('/tamara/{order}/failure', [\App\Http\Controllers\PaymentController::class, 'tamaraFailure'])->name('tamara.failure');
Route::get('/tamara/{order}/cancel', [\App\Http\Controllers\PaymentController::class, 'tamaraCancel'])->name('tamara.cancel');
Route::post('/tamara/notification', [\App\Http\Controllers\PaymentController::class, 'tamaraNotification'])->name('tamara.notification');

// Tabby Routes
Route::post('/payments/tabby/checkout', [\App\Http\Controllers\TabbyController::class, 'initiateTabbyCheckout'])->name('tabby.checkout');
Route::get('/payments/tabby/success', [\App\Http\Controllers\TabbyController::class, 'success'])->name('tabby.success');
Route::get('/payments/tabby/cancel', [\App\Http\Controllers\TabbyController::class, 'cancel'])->name('tabby.cancel');
Route::get('/payments/tabby/failure', [\App\Http\Controllers\TabbyController::class, 'failure'])->name('tabby.failure');

// --- Authenticated Customer Routes ---

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/checkout/{order}/payment', [\App\Http\Controllers\PaymentController::class, 'show'])->name('checkout.payment');
    Route::get('/checkout/{order}/bnpl', [\App\Http\Controllers\PaymentController::class, 'bnpl'])->name('checkout.bnpl');
    Route::post('/checkout/{order}/tamara-init', [\App\Http\Controllers\PaymentController::class, 'initTamara'])->name('tamara.init');

    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/{order}/email-invoice', [\App\Http\Controllers\OrderController::class, 'emailInvoice'])->name('orders.email_invoice');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Webhooks (No Auth Required) ---
Route::post('/checkout/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('checkout.webhook');



// --- Legal Pages ---
Route::view('/privacy-policy', 'privacy-policy')->name('privacy-policy');
Route::view('/refund-policy', 'refund-policy')->name('refund-policy');
Route::view('/terms', 'terms')->name('terms');

// --- Auth Routes (Breeze) ---
require __DIR__ . '/auth.php';
