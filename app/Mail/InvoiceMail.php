<?php

namespace App\Mail;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     * Only pass the Order model (which is serializable via SerializesModels).
     * The PDF is generated inside the mailable to avoid DOMDocument serialization issues.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'فاتورة طلبك #' . $this->order->id . ' - مناحل وادي الجربة',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.invoice',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generate PDF here (inside the mailable) to avoid serialization issues with DOMDocument.
        $pdf = Pdf::loadView('invoices.order', ['order' => $this->order]);

        return [
            Attachment::fromData(fn () => $pdf->output(), 'invoice-' . $this->order->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
