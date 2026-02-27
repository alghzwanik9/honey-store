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
        if ((int)$order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function downloadInvoice(Order $order)
    {
        if ((int)$order->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403);
        }

        $data = $order->getInvoiceData();
        $pdf = Pdf::loadView('invoices.order', $data);

        return $pdf->download('invoice-' . $order->id . '.pdf');
    }

    public function emailInvoice(Order $order)
    {
        if ((int)$order->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            \Illuminate\Support\Facades\Mail::to($order->user)->queue(new \App\Mail\InvoiceMail($order));
            return back()->with('success', 'تم إرسال الفاتورة إلى بريدك الإلكتروني بنجاح (قد يستغرق بضع دقائق).');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إرسال البريد الإلكتروني: ' . $e->getMessage());
        }
    }
}
