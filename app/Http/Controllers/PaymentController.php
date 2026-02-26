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

        if ($order->payment_method === 'tamara') {
            return $this->processTamaraCheckout($order);
        }

        // Keep simulated view for Tabby until implemented
        return view('checkout.bnpl', compact('order'));
    }

    private function processTamaraCheckout(Order $order)
    {
        $apiUrl = config('services.tamara.api_url');
        $merchantToken = config('services.tamara.merchant_token');

        if (!$merchantToken) {
            \Log::error('Tamara Merchant Token is missing.');
            return redirect()->route('checkout.payment', $order)->with('error', 'خدمة دفع تمارا غير متوفرة حالياً.');
        }

        // Prepare order items
        $items = $order->items->map(function ($item) {
            return [
                'reference_id' => (string) $item->product_id,
                'type' => 'physical',
                'name' => $item->product->name,
                'sku' => 'SKU-' . $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => [
                    'amount' => $item->price,
                    'currency' => 'SAR',
                ],
                'total_amount' => [
                    'amount' => $item->price * $item->quantity,
                    'currency' => 'SAR',
                ],
            ];
        })->toArray();

        // Split name for Tamara requirements
        $nameParts = explode(' ', $order->customer_name ?? 'العميل');
        $firstName = $nameParts[0];
        $lastName = count($nameParts) > 1 ? end($nameParts) : $firstName;

        $payload = [
            'order_reference_id' => (string) $order->id,
            'total_amount' => [
                'amount' => $order->total_amount,
                'currency' => 'SAR',
            ],
            'description' => 'طلب رقم #' . $order->id . ' من مناحل وادي الجرفة',
            'country_code' => 'SA',
            'payment_type' => 'PAY_BY_INSTALMENTS',
            'locale' => 'ar_SA',
            'items' => $items,
            'consumer' => [
                'first_name' => collect(str_split($firstName))->take(64)->join(''),
                'last_name' => collect(str_split($lastName))->take(64)->join(''),
                'phone_number' => $order->customer_phone ?? '0500000000',
                'email' => $order->user->email ?? 'customer@example.com',
            ],
            'shipping_address' => [
                'first_name' => collect(str_split($firstName))->take(64)->join(''),
                'last_name' => collect(str_split($lastName))->take(64)->join(''),
                'line1' => collect(str_split($order->address ?? 'Riyadh'))->take(128)->join(''),
                'city' => collect(str_split($order->city ?? 'Riyadh'))->take(128)->join(''),
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
            $response = Http::withToken($merchantToken)
                ->post($apiUrl . '/checkout', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Store Tamara Order ID if needed for future operations
                $order->update([
                    'payment_id' => $data['order_id'] ?? null
                ]);

                return redirect()->away($data['checkout_url']);
            }

            \Log::error('Tamara Checkout API Error: ', [
                'status' => $response->status(),
                'response' => $response->json(),
                'payload' => $payload
            ]);

            return redirect()->route('checkout.payment', $order)->with('error', 'تعذر تهيئة الدفع عبر تمارا حالياً.');

        } catch (\Exception $e) {
            \Log::error('Tamara Exception: ' . $e->getMessage());
            return redirect()->route('checkout.payment', $order)->with('error', 'حدث خطأ في الاتصال بخدمة تمارا.');
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
        // 1. Verify Payment Status via Tamara API
        $apiUrl = config('services.tamara.api_url');
        $merchantToken = config('services.tamara.merchant_token');
        $paymentId = $request->query('paymentId') ?? $order->payment_id;

        if ($paymentId) {
            try {
                // Determine order status from Tamara
                $response = Http::withToken($merchantToken)
                    ->get($apiUrl . '/orders/' . $paymentId);

                if ($response->successful()) {
                    $tamaraOrder = $response->json();
                    
                    // Possible statuses: NEW, APPROVED, AUTHORIZED, CANCELED, DECLINED...
                    $status = $tamaraOrder['status'] ?? '';
                    
                    if (in_array($status, ['APPROVED', 'AUTHORIZED', 'FULLY_CAPTURED'])) {
                        $order->update([
                            'payment_status' => 'paid',
                            'payment_id' => $paymentId
                        ]);

                        // Clear Cart
                        try {
                            session()->forget('cart');
                        } catch (\Exception $e) {
                            \Log::error('Failed to clear cart: ' . $e->getMessage());
                        }

                        // Send Invoice Email
                        try {
                            if ($order->user && $order->user->email) {
                                \Illuminate\Support\Facades\Mail::to($order->user)->queue(new \App\Mail\InvoiceMail($order));
                            }
                        } catch (\Exception $e) {
                            \Log::error('Failed to send invoice email: ' . $e->getMessage());
                        }

                        return redirect()->route('payment.success', ['order' => $order->id]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Tamara Verification Error: ' . $e->getMessage());
            }
        }

        // Fallback if verification fails but user is redirected here
        return redirect()->route('payment.success', ['order' => $order->id])
                         ->with('message', 'تم استلام طلبك، وجاري تأكيد الدفع مع تمارا.');
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
        $status = $payload['order_status'] ?? null;

        if ($orderId && $status) {
            $order = Order::find($orderId);
            if ($order && in_array($status, ['APPROVED', 'AUTHORIZED', 'FULLY_CAPTURED'])) {
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
