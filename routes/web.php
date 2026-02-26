<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

// --- Public Routes ---

Route::get('/', function () {
    // أضفنا with('category') لكي يجلب الأقسام مع المنتجات في خبطة واحدة
    $query = Product::with('category')
        ->where('is_active', true)
        ->where('stock', '>', 0);

    // Search Filter
    if (request('search')) {
        $query->where('name', 'like', '%' . request('search') . '%')
              ->orWhere('description', 'like', '%' . request('search') . '%');
    }

    // Category Filter
    if (request('category')) {
        $query->whereHas('category', function ($q) {
            $q->where('slug', request('category'));
        });
    }

    $products = $query->paginate(12);

    return view('home', ['products' => $products]);
})->name('home');

Route::get('/products/{product:slug}', function (Product $product) {
    return view('products.show', ['product' => $product]);
})->name('products.show');

// AI Chat Route (Public)
Route::post('/chat', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');

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
