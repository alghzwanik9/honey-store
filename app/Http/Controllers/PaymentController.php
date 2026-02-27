<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        // Ensure order belongs to user
        if ($order->user_id != auth()->id()) {
            return redirect()->route('dashboard');
        }

        // If paid, redirect to success/receipt
        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.success', $order);
        }

        return view('checkout.payment', [
            'order' => $order,
            'moyasarPublishableKey' => config('services.moyasar.publishable_key'),
        ]);
    }

    public function bnpl(Order $order)
    {
        if ($order->user_id != auth()->id()) {
            return redirect()->route('dashboard');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.success', $order);
        }

        // Return a loading/dispatch page for BNPL methods
        return view('checkout.bnpl', compact('order'));
    }

    /**
     * Non-blocking Tamara Initialization for AJAX
     */
    public function initTamara(Order $order)
    {
        if ($order->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            return $this->processTamaraCheckout($order, true);
        } catch (\Throwable $e) {
            \Log::error('Tamara AJAX Init Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function processTamaraCheckout(Order $order, $isJson = false)
    {
        $apiUrl = config('services.tamara.api_url');
        $merchantToken = config('services.tamara.merchant_token');

        if (!$merchantToken) {
            \Log::error('Tamara Merchant Token is missing.');
            $error = 'خدمة دفع تمارا غير متوفرة حالياً.';
            return $isJson ? response()->json(['error' => $error], 500) : redirect()->route('checkout.payment', $order)->with('error', $error);
        }

        // Format phone number (Tamara usually needs 9665xxxxxxxx format)
        $phone = $order->customer_phone ?? '0500000000';
        $phone = preg_replace('/[^0-9]/', '', $phone); // Remove any non-numeric
        if (str_starts_with($phone, '0')) {
            $phone = '966' . substr($phone, 1);
        } elseif (!str_starts_with($phone, '966')) {
            $phone = '966' . $phone;
        }

        // Prepare order items with precision rounding
        $items = $order->items->map(function ($item) {
            $unitPrice = round((float) $item->price, 2);
            $totalPrice = round($unitPrice * $item->quantity, 2);
            
            $productName = $item->product->name ?? 'منتج غير معروف';
            
            return [
                'reference_id' => (string) $item->product_id,
                'type' => 'physical',
                'name' => \Illuminate\Support\Str::limit($productName, 128, ''),
                'sku' => 'SKU-' . $item->product_id,
                'quantity' => (int) $item->quantity,
                'unit_price' => [
                    'amount' => $unitPrice,
                    'currency' => 'SAR',
                ],
                'total_amount' => [
                    'amount' => $totalPrice,
                    'currency' => 'SAR',
                ],
            ];
        })->toArray();

        // Split name for Tamara requirements
        $nameParts = explode(' ', $order->customer_name ?? 'العميل');
        $firstName = $nameParts[0];
        $lastName = count($nameParts) > 1 ? end($nameParts) : $firstName;

        // Calculate amounts (No VAT)
        $totalAmount = round((float) $order->total_amount, 2);
        $taxAmount = 0.00;

        $payload = [
            'order_reference_id' => (string) $order->id,
            'total_amount' => [
                'amount' => $totalAmount,
                'currency' => 'SAR',
            ],
            'tax_amount' => [
                'amount' => $taxAmount,
                'currency' => 'SAR',
            ],
            'shipping_amount' => [
                'amount' => 0.0,
                'currency' => 'SAR',
            ],
            'discount_amount' => [
                'amount' => 0.0,
                'currency' => 'SAR',
            ],
            'description' => 'طلب رقم #' . $order->id . ' من مناحل وادي الجرفة',
            'country_code' => 'SA',
            'payment_type' => 'PAY_BY_INSTALMENTS',
            'locale' => 'ar_SA',
            'items' => $items,
            'consumer' => [
                'first_name' => \Illuminate\Support\Str::limit($firstName, 64, ''),
                'last_name' => \Illuminate\Support\Str::limit($lastName, 64, ''),
                'phone_number' => $phone,
                'email' => $order->user->email ?? 'customer@example.com',
            ],
            'shipping_address' => [
                'first_name' => \Illuminate\Support\Str::limit($firstName, 64, ''),
                'last_name' => \Illuminate\Support\Str::limit($lastName, 64, ''),
                'line1' => \Illuminate\Support\Str::limit($order->address ?? 'Riyadh', 128, ''),
                'city' => \Illuminate\Support\Str::limit($order->city ?? 'Riyadh', 128, ''),
                'country_code' => 'SA',
            ],
            'merchant_url' => [
                'success' => route('tamara.success', ['order' => $order->id]),
                'failure' => route('tamara.failure', ['order' => $order->id]),
                'cancel' => route('tamara.cancel', ['order' => $order->id]),
                'notification' => route('tamara.notification', ['order' => $order->id]),
            ],
        ];

        try {
            $merchantToken = trim($merchantToken); // Ensure no spaces
            
            \Log::info('Initiating Tamara Checkout', ['order_id' => $order->id, 'url' => $apiUrl]);
            
            $response = Http::withToken($merchantToken)
                ->timeout(15)
                ->post($apiUrl . '/checkout', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                $order->update([
                    'payment_id' => $data['order_id'] ?? null
                ]);

                return $isJson ? response()->json(['checkout_url' => $data['checkout_url']]) : redirect()->away($data['checkout_url']);
            }

            $errorData = $response->json();
            $statusCode = $response->status();
            
            \Log::error('Tamara Checkout API Error: ', [
                'status' => $statusCode,
                'response' => $errorData,
                'payload' => $payload
            ]);

            $errorMsg = "فشل الربط مع تمارا (كود: $statusCode). ";
            $errorMsg .= $errorData['errors'][0]['message'] ?? $errorData['message'] ?? 'يرجى مراجعة الإعدادات.';

            return $isJson ? response()->json(['error' => $errorMsg], 400) : redirect()->route('checkout.payment', $order)->with('error', $errorMsg);

        } catch (\Throwable $e) {
            \Log::error('Tamara Exception: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('checkout.payment', $order)->with('error', 'خطأ تقني في تمارا: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $paymentId = $request->query('id');
        $status = $request->query('status');
        $message = $request->query('message');

        \Log::info('Moyasar Callback Start', $request->all());

        if ($status !== 'paid') {
            \Log::warning('Moyasar Callback: Status is not paid', ['status' => $status, 'message' => $message]);
            return redirect()->route('cart.index')->with('error', 'فشلت عملية الدفع: ' . $message);
        }

        try {
            // 1. Fetch Payment
            $payment = \Moyasar\Facades\Payment::fetch($paymentId);
            \Log::info('Moyasar Payment Fetched', ['payment_status' => $payment->status, 'amount' => $payment->amount]);

            if ($payment->status !== 'paid') {
                return redirect()->route('cart.index')->with('error', 'حالة الدفع غير صحيحة.');
            }

            // 2. Find Order (Best effort)
            $orderId = $request->query('order_id');
            if (!$orderId) {
                // Try to find in metadata description if not in query
                preg_match('/Order #(\d+)/', $payment->description, $matches);
                $orderId = $matches[1] ?? null;
            }

            if (!$orderId) {
                \Log::error('Moyasar Callback: Order ID not found');
                return redirect()->route('cart.index')->with('error', 'رقم الطلب غير موجود.');
            }

            $order = Order::findOrFail($orderId);
            \Log::info('Order Found', ['order_id' => $order->id]);

            // 3. Update Order Immediately (Requested by User)
            $order->update([
                // 'status' => 'paid', // Removed: ENUM constraint violation (pending, shipped, completed)
                'payment_status' => 'paid',
                'payment_id' => $paymentId
            ]);

            // 4. Clear Cart in Try-Catch (Requested by User)
            try {
                session()->forget('cart');
            } catch (\Exception $e) {
                \Log::error('Failed to clear cart: ' . $e->getMessage());
            }

            // 5. Send Invoice Email (Try/Catch to avoid blocking redirect if mail fails)
            try {
                if ($order->user && $order->user->email) {
                    // InvoiceMail generates the PDF internally to avoid DOMDocument serialization issues with queues
                    \Illuminate\Support\Facades\Mail::to($order->user)->queue(new \App\Mail\InvoiceMail($order));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send invoice email: ' . $e->getMessage());
            }

            // 6. Explicit Redirect (Requested by User)
            \Log::info('Redirecting to Success Page');
            return redirect()->route('payment.success', ['order' => $order->id]);

        } catch (\Exception $e) {
            \Log::error('Moyasar Callback Error: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        // Allow if user is owner OR if order is recent/paid (relaxing check for now to fix redirect issue)
        // In production, use signed URLs or session tokens for better security without strict auth.
        if (auth()->check() && $order->user_id != auth()->id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }


    /**
     * Handle Moyasar Webhook
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function webhook(Request $request)
    {
        // 1. Validate Input
        $id = $request->input('id');
        $status = $request->input('status');

        if (!$id || !$status) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        // 2. Fetch Payment from Moyasar for Security (Don't trust payload blindly)
        try {
            $payment = \Moyasar\Facades\Payment::fetch($id);

            if ($payment->status !== 'paid') {
                return response()->json(['message' => 'Payment not paid'], 200);
            }

            // 3. Find Order
            // Extract Order ID from description "Order #123"
            preg_match('/Order #(\d+)/', $payment->description, $matches);
            $orderId = $matches[1] ?? null;

            if (!$orderId) {
                \Log::error('Moyasar Webhook: Order ID not found in description: ' . $payment->description);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order = Order::find($orderId);

            if (!$order) {
                \Log::error('Moyasar Webhook: Order not found in DB: ' . $orderId);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // 4. Verify Amount
            if ($payment->amount != ($order->total_amount * 100)) {
                \Log::error('Moyasar Webhook: Amount mismatch for Order #' . $order->id);
                return response()->json(['message' => 'Amount mismatch'], 400);
            }

            // 5. Update Order Status
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'payment_id' => $id,
                ]);
            }

            return response()->json(['message' => 'Order updated'], 200);

        } catch (\Exception $e) {
            \Log::error('Moyasar Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing webhook'], 500);
        }
    }

    public function tamaraSuccess(Request $request, Order $order)
    {
        $apiUrl = config('services.tamara.api_url');
        $merchantToken = config('services.tamara.merchant_token');
        $paymentId = $request->query('paymentId') ?? $order->payment_id;

        \Log::info('Tamara Success Redirect', [
            'order_id' => $order->id,
            'paymentId_from_query' => $request->query('paymentId'),
            'paymentId_from_db' => $order->payment_id
        ]);

        if ($paymentId) {
            try {
                $response = Http::withToken(trim($merchantToken))
                    ->timeout(10)
                    ->get($apiUrl . '/orders/' . $paymentId);

                if ($response->successful()) {
                    $tamaraOrder = $response->json();
                    $status = strtoupper($tamaraOrder['status'] ?? '');

                    \Log::info('Tamara Order Status Verified', [
                        'order_id' => $order->id,
                        'tamara_status' => $status
                    ]);

                    if (in_array($status, ['APPROVED', 'AUTHORIZED', 'FULLY_CAPTURED', 'CAPTURED'])) {
                        if ($order->payment_status !== 'paid') {
                            $order->update([
                                'payment_status' => 'paid',
                                'payment_id' => $paymentId
                            ]);

                            session()->forget('cart');

                            // Send Invoice Email (Silent error)
                            try {
                                if ($order->user && $order->user->email) {
                                    \Illuminate\Support\Facades\Mail::to($order->user)->queue(new \App\Mail\InvoiceMail($order));
                                }
                            } catch (\Exception $e) {
                                \Log::error('Invoice Email Error in Callback: ' . $e->getMessage());
                            }
                        }

                        return redirect()->route('payment.success', ['order' => $order->id]);
                    }
                } else {
                    \Log::error('Tamara Verification Failed', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Tamara Success Exception: ' . $e->getMessage());
            }
        }

        // Final fallback: if we reach here and order is still pending, but reached success page
        return redirect()->route('payment.success', ['order' => $order->id])
                         ->with('message', 'تم استلام طلبك، وجاري تأكيد حالة الدفع من النظام.');
    }

    public function tamaraFailure(Request $request, Order $order)
    {
        \Log::warning('Tamara Payment Failed', ['order_id' => $order->id, 'request' => $request->all()]);
        return redirect()->route('checkout.payment', $order)->with('error', 'فشلت عملية الدفع عبر تمارا. يرجى المحاولة مرة أخرى أو اختيار طريقة دفع أخرى.');
    }

    public function tamaraCancel(Request $request, Order $order)
    {
        \Log::info('Tamara Payment Canceled by User', ['order_id' => $order->id]);
        return redirect()->route('checkout.payment', $order)->with('error', 'تم إلغاء عملية الدفع. يمكنك إعادة المحاولة حينما تكون مستعداً.');
    }

    public function tamaraNotification(Request $request)
    {
        // Tamara sends a webhook notification here when status changes
        $payload = $request->all();
        \Log::info('Tamara Webhook Received: ', $payload);
        
        $orderId = $payload['order_reference_id'] ?? null;
        $status = strtoupper($payload['order_status'] ?? '');

        if ($orderId && $status) {
            $order = Order::find($orderId);
            if ($order && in_array($status, ['APPROVED', 'AUTHORIZED', 'FULLY_CAPTURED', 'CAPTURED'])) {
                if ($order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'payment_id' => $payload['order_id'] ?? $order->payment_id,
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
