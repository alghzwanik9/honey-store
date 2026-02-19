<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>فاتورة #{{ $order->id }}</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 14px;
            color: #1f2937;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            line-height: 1.5;
        }
        
        /* Helper for Arabic Glyphs */
        @php
            $arabic = new \Arphp\Glyphs();
            $fix = function($text) use ($arabic) {
                if (!$text) return '';
                if (preg_match('/\p{Arabic}/u', $text)) {
                    return $arabic->utf8Glyphs($text);
                }
                return $text;
            };

            // Saudi Riyal SVG Base64 (Emerald-700 color: #047857)
            $samaSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1125 1257" fill="#047857"><path d="M699.62,1113.02h0c-20.06,44.48-33.32,92.75-38.4,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/><path d="M1085.73,895.8c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.33v-135.2l292.27-62.11c20.06-44.47,33.32-92.75,38.4-143.37l-330.68,70.27V66.13c-50.67,28.45-95.67,66.32-132.25,110.99v403.35l-132.25,28.11V0c-50.67,28.44-95.67,66.32-132.25,110.99v525.69l-295.91,62.88c-20.06,44.47-33.33,92.75-38.42,143.37l424.51-90.24c20.06-44.47,33.31-92.75,38.4-143.37l-424.51,90.24Z"/></svg>';
            $samaLogo = 'data:image/svg+xml;base64,' . base64_encode($samaSvg);
        @endphp

        .header-bg {
            background-color: #fef3c7; /* Amber-100 */
            height: 20px;
            width: 100%;
        }
        
        .container {
            padding: 40px;
        }

        .header {
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 2px solid #d97706; /* Amber-600 */
            padding-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header td {
            vertical-align: middle;
        }

        .store-name {
            font-size: 24px;
            font-weight: bold;
            color: #d97706;
            margin: 0;
        }
        
        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            margin: 0;
            text-align: left;
        }

        .info-table {
            margin-bottom: 40px;
        }
        
        .info-table td {
            padding: 8px;
            vertical-align: top;
        }

        .info-label {
            color: #6b7280;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }
        
        .info-value {
            color: #1f2937;
            font-size: 14px;
            font-weight: normal;
        }

        .items-table {
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
        }
        
        .items-table th {
            background-color: #d97706;
            color: white;
            padding: 12px 15px;
            font-weight: bold;
            text-align: right;
            font-size: 13px;
        }
        
        .items-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        
        .items-table tr:nth-child(even) {
            background-color: #fffbeb;
        }

        .totals-section td {
            vertical-align: top;
        }
        
        .totals-box {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .total-label {
            display: table-cell;
            font-weight: bold;
            color: #4b5563;
            text-align: right;
        }

        .total-value {
            display: table-cell;
            text-align: left;
            font-weight: bold;
            color: #1f2937;
        }
        
        .grand-total {
            background-color: #d97706;
            color: white;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
            font-weight: bold;
            font-size: 16px;
            display: table;
            width: 100%;
        }

        .footer {
            position: fixed;
            bottom: 40px;
            left: 40px;
            right: 40px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            line-height: 1.6;
        }
        
        .qr-code {
            text-align: center;
            margin-top: 10px;
        }
        
        .currency-logo {
            width: 12px;
            height: 12px;
            vertical-align: middle;
            display: inline-block;
            margin-right: 2px;
        }
    </style>
</head>
<body>
    <div class="header-bg"></div>
    
    <div class="container">
        <!-- Header -->
        <table class="header">
            <tr>
                <td width="60%">
                    <div class="store-name">{{ $fix('مناحل وادي الجرفة') }}</div>
                    <div style="color: #6b7280; font-size: 12px; margin-top: 5px;">{{ $fix('أجود أنواع العسل البلدي الطبيعي') }}</div>
                    <div style="color: #6b7280; font-size: 12px;">{{ $fix('سجل تجاري: 1010xxxxxx') }}</div>
                </td>
                <td width="40%" style="text-align: left;">
                    <div class="invoice-title">{{ $fix('فاتورة ضريبية') }}</div>
                    <div style="color: #9ca3af; margin-top: 5px; font-size: 14px;">#{{ $order->id }}</div>
                </td>
            </tr>
        </table>

        <!-- Info -->
        <table class="info-table">
            <tr>
                <td width="40%">
                    <span class="info-label">{{ $fix('بيانات العميل') }}</span>
                    <div class="info-value">
                        <strong>{{ $fix($order->user->name ?? 'عميل زائر') }}</strong><br>
                        {{ $order->user->email ?? '' }}<br>
                        <span style="direction: ltr; display: inline-block;">{{ $order->user->phone ?? '' }}</span><br>
                        {{ $fix($order->address ?? '') }}
                    </div>
                </td>
                <td width="30%">
                    <span class="info-label">{{ $fix('تفاصيل الفاتورة') }}</span>
                    <div class="info-value">
                        {{ $fix('رقم الطلب:') }} #{{ $order->id }}<br>
                        {{ $fix('تاريخ الإصدار:') }} {{ $order->created_at->format('Y/m/d') }}<br>
                        {{ $fix('طريقة الدفع:') }} {{ $fix($order->payment_method ?? 'البطاقة الائتمانية') }}
                    </div>
                </td>
                <td width="30%">
                    <span class="info-label">{{ $fix('حالة الدفع') }}</span>
                    <div class="info-value" style="color: {{ $order->payment_status == 'paid' ? '#059669' : '#dc2626' }}; font-weight: bold; font-size: 16px;">
                        {{ $fix($order->payment_status == 'paid' ? 'مدفوع' : 'غير مدفوع') }}
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="border-top-right-radius: 6px;">{{ $fix('المنتج') }}</th>
                    <th style="text-align: center;">{{ $fix('الكمية') }}</th>
                    <th>{{ $fix('سعر الوحدة') }}</th>
                    <th style="border-top-left-radius: 6px;">{{ $fix('الإجمالي') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $fix($item->product->name) }}</strong>
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td>
                        {{ number_format($item->price, 2) }} 
                        <img src="{{ $samaLogo }}" class="currency-logo" alt="SAR">
                    </td>
                    <td style="font-weight: bold;">
                        {{ number_format($item->price * $item->quantity, 2) }} 
                        <img src="{{ $samaLogo }}" class="currency-logo" alt="SAR">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals Logic -->
        @php
            $totalAmount = $order->total_amount;
            $subTotal = $totalAmount / 1.15; // Extracting base amount from inclusive total
            $vatAmount = $totalAmount - $subTotal;
        @endphp

        <!-- Totals Layout -->
        <table class="totals-section">
            <tr>
                <td width="60%" style="padding-left: 20px;">
                    <div class="qr-code">
                        {{-- QR Code: Contains essential invoice data (TLV format is standard for ZATCA but URL is fine for simple use) --}}
                        <img src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->color(217, 119, 6)->generate(route('orders.show', $order->id))) }}" alt="QR Code">
                        <div style="font-size: 10px; color: #6b7280; margin-top: 5px;">{{ $fix('امسح الكود لتفاصيل الطلب') }}</div>
                    </div>
                    <div style="margin-top: 20px; font-size: 10px; color: #9ca3af;">
                        * {{ $fix('الأسعار تشمل ضريبة القيمة المضافة 15%') }}
                    </div>
                </td>
                <td width="40%">
                    <div class="totals-box">
                        <div class="total-row">
                            <span class="total-label">{{ $fix('المجموع الفرعي (غير شامل الضريبة)') }}</span>
                            <span class="total-value">{{ number_format($subTotal, 2) }} <img src="{{ $samaLogo }}" class="currency-logo" alt="SAR"></span>
                        </div>
                        <div class="total-row">
                            <span class="total-label">{{ $fix('ضريبة القيمة المضافة (15%)') }}</span>
                            <span class="total-value">{{ number_format($vatAmount, 2) }} <img src="{{ $samaLogo }}" class="currency-logo" alt="SAR"></span>
                        </div>
                        <div class="grand-total">
                            <span style="float: right;">{{ $fix('الإجمالي النهائي') }}</span>
                            <span style="float: left;">{{ number_format($totalAmount, 2) }} <img src="{{ $samaLogo }}" class="currency-logo" style="filter: brightness(0) invert(1);" alt="SAR"></span>
                            <div style="clear: both;"></div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <div>{{ $fix('شكراً لثقتكم بمناحل وادي الجرفة') }}</div>
        <div style="margin-top: 5px; direction: ltr;">Info@honey-store.com | +966 50 000 0000</div>
        <div style="margin-top: 5px; font-size: 10px;">{{ $fix('تم إصدار هذه الفاتورة إلكترونياً') }}</div>
    </div>
</body>
</html>
