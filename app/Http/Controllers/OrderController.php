<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.product')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        // specific user check
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function downloadInvoice(Order $order)
    {
        if ($order->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        // Pass the order to the view.
        // The view handles the logic of extracting VAT from the total_amount (which is tax-inclusive).
        $data = [
            'order' => $order,
        ];

        // QR Code generation is handled within the view for PDF compatibility
        $pdf = Pdf::loadView('invoices.order', $data);

        return $pdf->download('invoice-' . $order->id . '.pdf');
    }

    public function emailInvoice(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Pass the order to the view.
        $data = [
            'order' => $order,
        ];

        $pdf = Pdf::loadView('invoices.order', $data);

        try {
            \Illuminate\Support\Facades\Mail::to($order->user)->send(new \App\Mail\InvoiceMail($order, $pdf));
            return back()->with('success', 'تم إرسال الفاتورة إلى بريدك الإلكتروني بنجاح.');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إرسال البريد الإلكتروني: ' . $e->getMessage());
        }
    }
}
