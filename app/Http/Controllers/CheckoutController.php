<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page.
     * This route is protected by 'auth' middleware, so guests
     * will be redirected to login automatically, then back here.
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:20',
            'postal_code' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cod,card,tamara,tabby',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'السلة فارغة!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Create Order
        $order = \App\Models\Order::create([
            'customer_name' => auth()->user()->name,
            'customer_phone' => $validated['customer_phone'],
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'status' => 'pending',
            'address' => $validated['address'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'],
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        // Create Order Items
        foreach ($cart as $id => $details) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);
        }

        // Redirect based on payment method
        if ($order->payment_method === 'card') {
            return redirect()->route('checkout.payment', $order->id);
        }

        if (in_array($order->payment_method, ['tamara', 'tabby'])) {
            return redirect()->route('checkout.bnpl', $order->id);
        }

        // For COD, we can clear the cart immediately
        session()->forget('cart');

        return redirect()->route('checkout.success', $order->id);
    }
}
