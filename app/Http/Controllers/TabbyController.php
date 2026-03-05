<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TabbyController extends Controller
{
    /**
     * Initiate checkout process with Tabby API
     */
    public function initiateTabbyCheckout(Request $request)
    {
        // Read keys from config or env
        $publicKey = config('services.tabby.public_key') ?? env('TABBY_PUBLIC_KEY', 'pk_test_placeholder');
        $merchantCode = config('services.tabby.merchant_code') ?? env('TABBY_MERCHANT_CODE', 'merchant_test');

        // Prepare the payload with placeholders
        $payload = [
            'payment' => [
                'amount' => '250.00', // Placeholder amount
                'currency' => 'SAR',  // Placeholder currency
                'description' => 'Test order from Honey Store',
                'buyer' => [
                    'phone' => '+966500000000', // Placeholder phone
                    'email' => 'card.success@tabby.ai', // Default test email for Tabby
                    'name' => 'John Doe',
                    'dob' => '1990-01-01',
                ],
                'shipping_address' => [
                    'city' => 'Riyadh',
                    'address' => 'Test Address St.',
                    'zip' => '12345',
                ],
                'order' => [
                    'tax_amount' => '0.00',
                    'shipping_amount' => '0.00',
                    'discount_amount' => '0.00',
                    'updated_at' => now()->toIso8601String(),
                    'reference_id' => 'ORD-123456',
                    'items' => [
                        [
                            'title' => 'عسل سدر بلدي',
                            'description' => 'عسل فاخر',
                            'quantity' => 1,
                            'unit_price' => '250.00',
                            'category' => 'Honey',
                        ]
                    ]
                ],
            ],
            'lang' => 'ar',
            'merchant_code' => $merchantCode,
            'merchant_urls' => [
                'success' => route('tabby.success'),
                'cancel' => route('tabby.cancel'),
                'failure' => route('tabby.failure'),
            ]
        ];

        // Send POST request to Tabby API v2 Checkout
        $response = Http::withToken($publicKey)->post('https://api.tabby.ai/api/v2/checkout', $payload);

        if ($response->successful()) {
            $url = $response->json('configuration.available_products.installments.web_url');
            if ($url) {
                return redirect()->away($url);
            }
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Tabby API error', 'details' => $response->json()], 400);
    }

    public function success(Request $request) 
    {
        // Logic when payment is successful
        return "Payment successful via Tabby! Reference ID: " . $request->get('payment_id');
    }

    public function cancel(Request $request) 
    {
        // Logic when customer cancels payment
        return "Payment cancelled by the user.";
    }

    public function failure(Request $request) 
    {
        // Logic when payment fails
        return "Payment failed or rejected.";
    }
}
