<?php

namespace App\Traits;

trait HasZatcaQr
{
    /**
     * Get Invoice Data for PDF
     */
    public function getInvoiceData()
    {
        $totalAmount = (float) $this->total_amount;
        
        // No VAT calculations for non-registered sellers
        $subTotal = $totalAmount;
        $vatAmount = 0.00;

        $sellerName = "متجر رِواء";
        $crNumber = config('services.store.cr_number', '1010000000');
        
        // For non-VAT sellers, the QR can just be a link to the order details
        $qrCodePayload = route('orders.show', $this->id);

        return [
            'order' => $this,
            'subTotal' => $subTotal,
            'vatAmount' => $vatAmount,
            'qrCodePayload' => $qrCodePayload,
            'crNumber' => $crNumber,
        ];
    }
}
