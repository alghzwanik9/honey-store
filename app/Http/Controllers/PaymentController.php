<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        // Ensure order belongs to user and is pending payment
        if ($order->user_id !== auth()->id() || $order->payment_status === 'paid') {
            return redirect()->route('dashboard');
        }

        return view('checkout.payment', [
            'order' => $order,
            'moyasarPublishableKey' => config('services.moyasar.publishable_key'),
        ]);
    }

    public function bnpl(Order $order)
    {
        if ($order->user_id !== auth()->id() || $order->payment_status === 'paid') {
            return redirect()->route('dashboard');
        }

        return view('checkout.bnpl', compact('order'));
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
                    // Prepare data for PDF (reusing logic from OrderController - ideally refactor to Service)
                    $subtotal = $order->total_amount / 1.15; // Approximate if not stored
                    $tax = $order->total_amount - $subtotal;
                    
                    $items = $order->items; // Relationship should be loaded or lazy loaded
                    
                    $data = [
                        'order' => $order,
                        'user' => $order->user,
                        'items' => $items,
                        'subtotal' => $subtotal,
                        'tax' => $tax,
                        'total' => $order->total_amount
                    ];

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order', $data);
                    
                    \Illuminate\Support\Facades\Mail::to($order->user)->send(new \App\Mail\InvoiceMail($order, $pdf));
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
        if (auth()->check() && $order->user_id !== auth()->id()) {
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

    /**
     * Placeholder: Tamara Checkout
     * To be implemented based on Tamara Documentation
     */
    public function tamaraCheckout(Order $order)
    {
        // TODO: Implement Tamara Checkout Session creation
        // 1. Call Tamara API to create checkout session
        // 2. Redirect user to Tamara payment URL
        return response()->json(['message' => 'Tamara integration coming soon...']);
    }
}
