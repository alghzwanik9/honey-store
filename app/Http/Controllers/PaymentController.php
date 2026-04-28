<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Order $order)
    {
        // Ensure order belongs to user
        if ($order->user_id != Auth::id()) {
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
        if ($order->user_id != Auth::id()) {
            return redirect()->route('dashboard');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.success', $order);
        }

        // Return a loading/dispatch page for BNPL methods
        return view('checkout.bnpl', compact('order'));
    }



    public function callback(Request $request)
    {
        $paymentId = $request->query('id');
        $status = $request->query('status');
        $message = $request->query('message');

        Log::info('Moyasar Callback Start', [
            'id' => $paymentId,
            'status' => $status,
            'message' => $message,
            'order_id_param' => $request->query('order_id')
        ]);

        if ($status !== 'paid') {
            Log::warning('Moyasar Callback: Status is not paid', ['status' => $status, 'message' => $message]);
            return redirect()->route('cart.index')->with('error', 'فشلت عملية الدفع: ' . $message);
        }

        try {
            // Check if API Key is set
            if (!config('moyasar.key')) {
                Log::error('Moyasar Callback Error: API Key is not configured in config/moyasar.php');
                return redirect()->route('cart.index')->with('error', 'خطأ في إعدادات بوابة الدفع (API Key Missing).');
            }

            // 1. Fetch Payment
            $payment = \Moyasar\Facades\Payment::fetch($paymentId);
            Log::info('Moyasar Payment Fetched', [
                'payment_status' => $payment->status, 
                'amount' => $payment->amount,
                'description' => $payment->description
            ]);

            if ($payment->status !== 'paid') {
                return redirect()->route('cart.index')->with('error', 'حالة الدفع من ميسر غير مؤكدة.');
            }

            // 2. Find Order
            $orderId = $request->query('order_id');
            if (!$orderId) {
                // Try to find in metadata description if not in query
                preg_match('/Order #(\d+)/', $payment->description, $matches);
                $orderId = $matches[1] ?? null;
            }

            if (!$orderId) {
                Log::error('Moyasar Callback: Order ID not found');
                return redirect()->route('cart.index')->with('error', 'رقم الطلب غير موجود.');
            }

            $order = Order::findOrFail($orderId);
            Log::info('Order Found', ['order_id' => $order->id]);

            // 3. Update Order Immediately
            $order->update([
                'payment_status' => 'paid',
                'payment_id' => $paymentId,
                'status' => 'processing' // Move to processing status
            ]);

            // 4. Clear Cart
            try {
                session()->forget('cart');
            } catch (\Exception $e) {
                Log::error('Failed to clear cart: ' . $e->getMessage());
            }

            // 5. Send Invoice Email (Wrapped to prevent failure)
            try {
                if ($order->user && $order->user->email) {
                    \Illuminate\Support\Facades\Mail::to($order->user)->queue(new \App\Mail\InvoiceMail($order));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send invoice email: ' . $e->getMessage());
            }

            Log::info('Payment Processed Successfully, Redirecting to Success Page');
            return redirect()->route('payment.success', ['order' => $order->id]);

        } catch (\Exception $e) {
            Log::error('Moyasar Callback Error Trace: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('cart.index')->with('error', 'حدث خطأ أثناء تأكيد الدفع: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        // Allow if user is owner OR if order is recent/paid (relaxing check for now to fix redirect issue)
        // In production, use signed URLs or session tokens for better security without strict auth.
        if (Auth::check() && $order->user_id != Auth::id()) {
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
                Log::error('Moyasar Webhook: Order ID not found in description: ' . $payment->description);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order = Order::find($orderId);

            if (!$order) {
                Log::error('Moyasar Webhook: Order not found in DB: ' . $orderId);
                return response()->json(['message' => 'Order not found'], 404);
            }

            // 4. Verify Amount
            if ($payment->amount != ($order->total_amount * 100)) {
                Log::error('Moyasar Webhook: Amount mismatch for Order #' . $order->id);
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
            Log::error('Moyasar Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing webhook'], 500);
        }
    }


}
