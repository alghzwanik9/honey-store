<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Illuminate\Http\Request;

class TabbyController extends Controller
{
    /**
     * Non-blocking Tabby Initialization for AJAX (Simulation of successful flow)
     */
    public function initTabby(Order $order)
    {
        if ($order->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Read keys from config or env
            $publicKey = config('services.tabby.public_key');
            
            // Check if keys are available - logic simulation for development
            if (!$publicKey) {
                // Return a simulation URL to directly go to success for now (As requested by the user)
                return response()->json([
                    'checkout_url' => route('tabby.success', ['order' => $order->id])
                ]);
            }

            // Real implementation logic (Placeholder if keys exist)
            return $this->initiateTabbyCheckoutLocal($order);

        } catch (\Throwable $e) {
            Log::error('Tabby AJAX Init Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Initiate checkout process with Tabby API
     */
    private function initiateTabbyCheckoutLocal(Order $order)
    {
        $publicKey = config('services.tabby.public_key') ?? env('TABBY_PUBLIC_KEY', 'pk_test_placeholder');
        $merchantCode = config('services.tabby.merchant_code') ?? env('TABBY_MERCHANT_CODE', 'merchant_test');

        // Prepare order items
        $items = $order->items->map(function ($item) {
            $productName = $item->product->name ?? 'منتج غير معروف';
            return [
                'title' => \Illuminate\Support\Str::limit($productName, 128, ''),
                'description' => 'عنصر من الطلب',
                'quantity' => (int) $item->quantity,
                'unit_price' => number_format((float) $item->price, 2, '.', ''),
                'category' => 'Physical',
                'reference_id' => (string) $item->product_id,
            ];
        })->toArray();

        // Prepare the payload with placeholders
        $payload = [
            'payment' => [
                'amount' => number_format((float) $order->total_amount, 2, '.', ''),
                'currency' => 'SAR',
                'description' => 'طلب رقم #' . $order->id . ' من متجر رِواء',
                'buyer' => [
                    'phone' => $order->customer_phone ?? '+966500000000',
                    'email' => $order->user->email ?? 'card.success@tabby.ai',
                    'name' => $order->customer_name ?? 'العميل',
                    'dob' => '1990-01-01',
                ],
                'shipping_address' => [
                    'city' => $order->city ?? 'Riyadh',
                    'address' => $order->address ?? 'Test Address St.',
                    'zip' => '12345',
                ],
                'order' => [
                    'tax_amount' => '0.00',
                    'shipping_amount' => '0.00',
                    'discount_amount' => '0.00',
                    'updated_at' => now()->toIso8601String(),
                    'reference_id' => (string) $order->id,
                    'items' => $items
                ],
            ],
            'lang' => 'ar',
            'merchant_code' => $merchantCode,
            'merchant_urls' => [
                'success' => route('tabby.success', ['order' => $order->id]),
                'cancel' => route('tabby.cancel', ['order' => $order->id]),
                'failure' => route('tabby.failure', ['order' => $order->id]),
            ]
        ];

        // Send POST request to Tabby API v2 Checkout
        $response = Http::withToken($publicKey)->post('https://api.tabby.ai/api/v2/checkout', $payload);

        if ($response->successful()) {
            $data = $response->json();
            $url = $data['configuration']['available_products']['installments']['web_url'] ?? null;
            
            if ($url) {
                return response()->json(['checkout_url' => $url]);
            }
            return response()->json(['error' => 'لم يتم إرجاع رابط الدفع من تابي'], 400);
        }

        $errorData = $response->json();
        Log::error('Tabby Checkout API Error:', $errorData);
        return response()->json(['error' => 'تعذر الاتصال بخدمة تابي حالياً.'], 400);
    }

    public function success(Request $request, Order $order) 
    {
        // Simulation: Since keys are not ready, we will immediately just process the order as paid.
        Log::info('Tabby Success Simulated Direct Hit', ['order_id' => $order->id]);
        
        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'payment_id' => 'tabby_simulated_' . rand(1000, 9999)
            ]);

            session()->forget('cart');

            // Send Invoice Email (Silent error)
            try {
                if ($order->user && $order->user->email) {
                    \Illuminate\Support\Facades\Mail::to($order->user)->queue(new \App\Mail\InvoiceMail($order));
                }
            } catch (\Exception $e) {
                Log::error('Invoice Email Error in Tabby Callback: ' . $e->getMessage());
            }
        }

        return redirect()->route('payment.success', ['order' => $order->id]);
    }

    public function cancel(Request $request, Order $order) 
    {
        Log::info('Tabby Payment Canceled by User', ['order_id' => $order->id]);
        return redirect()->route('checkout.payment', $order)->with('error', 'تم إلغاء عملية الدفع عبر تابي. يمكنك إعادة المحاولة حينما تكون مستعداً.');
    }

    public function failure(Request $request, Order $order) 
    {
        Log::warning('Tabby Payment Failed', ['order_id' => $order->id]);
        return redirect()->route('checkout.payment', $order)->with('error', 'فشلت عملية الدفع عبر تابي. يرجى المحاولة مرة أخرى.');
    }
}
