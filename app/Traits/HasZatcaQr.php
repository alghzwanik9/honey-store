<?php

namespace App\Traits;

trait HasZatcaQr
{
    /**
     * ZATCA TLV Base64 Encoding
     */
    public function generateZatcaTlv($seller, $vat, $time, $total, $tax)
    {
        $data = [
            [1, $seller],
            [2, $vat],
            [3, $time],
            [4, (string) number_format($total, 2, '.', '')],
            [5, (string) number_format($tax, 2, '.', '')],
        ];

        $tlv = "";
        foreach ($data as $item) {
            $tag = chr($item[0]);
            $value = $item[1];
            $length = chr(strlen($value));
            $tlv .= $tag . $length . $value;
        }

        return base64_encode($tlv);
    }

    /**
     * Get Invoice Data for PDF
     */
    public function getInvoiceData()
    {
        $totalAmount = (float) $this->total_amount;
        $subTotal = round($totalAmount / 1.15, 2);
        $vatAmount = round($totalAmount - $subTotal, 2);

        $sellerName = "مناحل وادي الجرفة";
        $vatNumber = config('services.store.vat_number', '302000000000003');
        $crNumber = config('services.store.cr_number', '1010000000');
        $timestamp = $this->created_at->toIso8601String();

        return [
            'order' => $this,
            'subTotal' => $subTotal,
            'vatAmount' => $vatAmount,
            'qrCodePayload' => $this->generateZatcaTlv($sellerName, $vatNumber, $timestamp, $totalAmount, $vatAmount),
            'vatNumber' => $vatNumber,
            'crNumber' => $crNumber,
        ];
    }
}
