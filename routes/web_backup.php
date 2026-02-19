<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // أضفنا with('category') لكي يجلب الأقسام مع المنتجات في خبطة واحدة
    $products = Product::with('category')
        ->where('is_active', true)
        ->where('stock', '>', 0)
        ->get();

    return view('home', ['products' => $products]);
});
Route::get('/products/{product:slug}', function (Product $product) {
    return view('products.show', ['product' => $product]);
})->name('products.show');

// Cart Routes
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
