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
            font-size: 13px;
            color: #1a1e26;
            margin: 0;
            padding: 0;
            background-color: #fdfaf6; /* Creamy background */
            line-height: 1.6;
        }
        
        @php
            $arabic = new \Arphp\Glyphs();
            $fix = function($text) use ($arabic) {
                if (!$text) return '';
                if (preg_match('/\p{Arabic}/u', $text)) {
                    return $arabic->utf8Glyphs($text);
                }
                return $text;
            };

            // Logo conversion to base64
            $logoPath = public_path('images/logo.png');
            $logoData = '';
            if (file_exists($logoPath)) {
                $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }
        @endphp

        .top-stripe {
            background-color: #0f172a; /* Navy */
            height: 8px;
            width: 100%;
        }
        
        .container {
            padding: 40px;
        }

        .header {
            width: 100%;
            margin-bottom: 40px;
            border-bottom: 2px solid #c08d5d; /* Gold */
            padding-bottom: 25px;
        }

        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 10px;
        }

        .store-name {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            display: block;
        }
        
        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #c08d5d; /* Gold */
            margin: 0;
            text-align: left;
        }

        .info-table {
            width: 100%;
            margin-bottom: 40px;
        }
        
        .info-card {
            background: #ffffff;
            padding: 20px;
            border-radius: 15px;
            border: 1px solid rgba(192, 141, 93, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .info-label {
            color: #64748b;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 6px;
            display: block;
            text-transform: uppercase;
        }
        
        .info-value {
            color: #0f172a;
            font-size: 13px;
        }

        .items-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            padding: 15px;
            font-weight: bold;
            text-align: right;
            font-size: 12px;
        }
        
        .items-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            background-color: #ffffff;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }

        /* Fixed Layout for Totals - Using table for stability */
        .totals-section {
            width: 100%;
            margin-top: 20px;
        }

        .qr-cell {
            vertical-align: top;
            padding-top: 10px;
        }

        .totals-cell {
            width: 320px;
            vertical-align: top;
        }

        .totals-box {
            background: #0f172a;
            padding: 25px;
            border-radius: 20px;
            color: #ffffff;
            width: 100%;
        }

        .total-table {
            width: 100%;
            border-collapse: collapse;
        }

        .total-row-td {
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .total-label {
            font-size: 12px;
            color: #94a3b8;
            text-align: right;
        }

        .total-value {
            font-weight: bold;
            color: #ffffff;
            text-align: left;
        }
        
        .grand-total-label {
            padding-top: 15px;
            font-weight: bold;
            font-size: 18px;
            color: #c08d5d; /* Gold */
            text-align: right;
        }

        .grand-total-value {
            padding-top: 15px;
            font-weight: bold;
            font-size: 18px;
            color: #c08d5d; /* Gold */
            text-align: left;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
            color: #64748b;
            font-size: 11px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="top-stripe"></div>
    
    <div class="container">
        <!-- Header -->
        <table class="header">
            <tr>
                <td width="60%">
                    @if($logoData)
                        <img src="{{ $logoData }}" class="logo" alt="Logo">
                    @endif
                    <div class="store-name">{{ $fix('مناحل وادي الجرفة') }}</div>
                    <div style="color: #64748b; font-size: 11px; margin-top: 3px;">{{ $fix('أجود أنواع العسل البلدي الطبيعي') }}</div>
                    <div style="color: #64748b; font-size: 11px;">
                        {{ $fix('الرقم الضريبي:') }} <span style="direction: ltr;">{{ $vatNumber }}</span><br>
                        {{ $fix('السجل التجاري:') }} <span style="direction: ltr;">{{ $crNumber }}</span>
                    </div>
                </td>
                <td width="40%" style="text-align: left;">
                    <div class="invoice-title">{{ $fix('فاتورة ضريبية مبسطة') }}</div>
                    <div style="color: #64748b; margin-top: 8px; font-size: 14px; font-weight: bold;">#{{ $order->id }}</div>
                    <div style="color: #94a3b8; font-size: 11px; margin-top: 4px;">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                </td>
            </tr>
        </table>

        <!-- Info Cards -->
        <table class="info-table">
            <tr>
                <td width="50%" style="padding-left: 10px;">
                    <div class="info-card">
                        <span class="info-label">{{ $fix('العميل') }}</span>
                        <div class="info-value">
                            <strong>{{ $fix($order->user->name ?? 'عميل زائر') }}</strong><br>
                            {{ $order->user->email ?? '' }}<br>
                            <span style="direction: ltr; display: inline-block;">{{ $order->customer_phone ?? $order->user->phone ?? '' }}</span><br>
                            {{ $fix($order->address ?? '') }}
                        </div>
                    </div>
                </td>
                <td width="50%" style="padding-right: 10px;">
                    <div class="info-card">
                        <span class="info-label">{{ $fix('طريقة الدفع والحالة') }}</span>
                        <div class="info-value">
                            {{ $fix('الطريقة:') }} {{ $fix($order->payment_method_label ?? $order->payment_method) }}<br>
                            {{ $fix('تاريخ الطلب:') }} {{ $order->created_at->format('Y/m/d') }}<br>
                            <div style="margin-top: 8px; color: {{ $order->payment_status == 'paid' ? '#10b981' : '#ef4444' }}; font-weight: bold; font-size: 15px;">
                                {{ $fix($order->payment_status == 'paid' ? 'مدفوع ✓' : 'بانتظار الدفع') }}
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>{{ $fix('المنتج') }}</th>
                    <th style="text-align: center;">{{ $fix('الكمية') }}</th>
                    <th>{{ $fix('السعر') }}</th>
                    <th style="text-align: left;">{{ $fix('الإجمالي') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong style="color: #0f172a;">{{ $fix($item->product->name) }}</strong>
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td>
                        {{ number_format($item->price, 2) }} {{ $fix('ر.س') }}
                    </td>
                    <td style="font-weight: bold; color: #0f172a; text-align: left;">
                        {{ number_format($item->price * $item->quantity, 2) }} {{ $fix('ر.س') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Calculation & QR Section -->
        <table class="totals-section">
            <tr>
                <td class="qr-cell">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(90)->color(15, 23, 42)->generate($qrCodePayload)) }}" alt="ZATCA QR">
                    <div style="font-size: 9px; color: #94a3b8; margin-top: 5px; text-align: right; width: 90px; margin-right: 0;">{{ $fix('فاتورة ضريبية معتمدة') }}</div>
                </td>
                <td class="totals-cell">
                    <div class="totals-box">
                        <table class="total-table">
                            <tr>
                                <td class="total-row-td total-label">{{ $fix('المجموع الفرعي') }}</td>
                                <td class="total-row-td total-value">{{ number_format($subTotal, 2) }} {{ $fix('ر.س') }}</td>
                            </tr>
                            <tr>
                                <td class="total-row-td total-label">{{ $fix('الضريبة (15%)') }}</td>
                                <td class="total-row-td total-value">{{ number_format($vatAmount, 2) }} {{ $fix('ر.س') }}</td>
                            </tr>
                            <tr>
                                <td class="grand-total-label">{{ $fix('الإجمالي النهائي') }}</td>
                                <td class="grand-total-value">{{ number_format($order->total_amount, 2) }} {{ $fix('ر.س') }}</td>
                            </tr>
                        </table>
                        <div style="font-size: 9px; color: #94a3b8; margin-top: 15px; text-align: center;">
                            * {{ $fix('جميع الأسعار تشمل ضريبة القيمة المضافة') }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer">
            <div style="font-weight: bold; color: #0f172a; margin-bottom: 5px;">{{ $fix('شكراً لاختياركم مناحل وادي الجرفة') }}</div>
            <div style="direction: ltr;">www.honey-store.com | Support@honey-store.com</div>
            <div style="margin-top: 8px; font-size: 9px;">{{ $fix('تم إصدار هذه الفاتورة إلكترونياً وهي معتمدة من هيئة الزكاة والضريبة والجمارك') }}</div>
        </div>
    </div>
</body>
</html>
