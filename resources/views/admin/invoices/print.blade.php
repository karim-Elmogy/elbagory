<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Tahoma', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: #fff;
            padding: 20px;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .info-box {
            flex: 1;
        }
        
        .info-box h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
        }
        
        .info-box p {
            margin: 5px 0;
            font-size: 13px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        table thead {
            background: #2c3e50;
            color: #fff;
        }
        
        table th {
            padding: 12px;
            text-align: right;
            font-weight: bold;
        }
        
        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #ddd;
        }
        
        table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-end {
            text-align: left;
        }
        
        .summary {
            margin-top: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        
        .summary-row:last-child {
            border-bottom: none;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #2c3e50;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-draft {
            background: #6c757d;
            color: #fff;
        }
        
        .status-final {
            background: #28a745;
            color: #fff;
        }
        
        .status-cancelled {
            background: #dc3545;
            color: #fff;
        }
        
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        
        .btn:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">طباعة</button>
        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn" style="background: #6c757d;">رجوع</a>
    </div>
    
    <div class="container">
        <div class="header">
            <h1>فاتورة جملة</h1>
            <p>رقم الفاتورة: <strong>{{ $invoice->invoice_number }}</strong></p>
            <p>تاريخ الفاتورة: {{ $invoice->invoice_date->format('Y-m-d') }}</p>
        </div>
        
        <div class="invoice-info">
            <div class="info-box">
                <h3>معلومات العميل</h3>
                <p><strong>الاسم:</strong> {{ $invoice->customer->name ?? 'غير معروف' }}</p>
                <p><strong>الهاتف:</strong> {{ $invoice->customer->phone ?? '-' }}</p>
                <p><strong>البريد:</strong> {{ $invoice->customer->email ?? '-' }}</p>
                @if($invoice->customer->company_name)
                    <p><strong>اسم الشركة:</strong> {{ $invoice->customer->company_name }}</p>
                @endif
                @if($invoice->customer->address)
                    <p><strong>العنوان:</strong> {{ $invoice->customer->address }}</p>
                @endif
            </div>
            
            <div class="info-box">
                <h3>معلومات الفاتورة</h3>
                <p><strong>طريقة الدفع:</strong>
                    @switch($invoice->payment_method)
                        @case('cash') نقدي @break
                        @case('bank_transfer') تحويل بنكي @break
                        @case('credit') آجل @break
                        @default {{ $invoice->payment_method }}
                    @endswitch
                </p>
                @if($invoice->credit_days)
                    <p><strong>أيام الآجل:</strong> {{ $invoice->credit_days }} يوم</p>
                @endif
                <p><strong>الحالة:</strong>
                    <span class="status-badge status-{{ $invoice->status }}">
                        @switch($invoice->status)
                            @case('draft') مسودة @break
                            @case('final') نهائية @break
                            @case('cancelled') ملغاة @break
                            @default {{ $invoice->status }}
                        @endswitch
                    </span>
                </p>
                <p><strong>أنشئ بواسطة:</strong> {{ $invoice->createdBy->name ?? '-' }}</p>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-end">المنتج</th>
                    <th class="text-center">الكمية</th>
                    <th class="text-center">سعر الوحدة</th>
                    <th class="text-center">الخصم %</th>
                    <th class="text-center">مبلغ الخصم</th>
                    <th class="text-center">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->product->name ?? 'منتج محذوف' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">{{ number_format($item->unit_price, 2) }} ج.م</td>
                        <td class="text-center">{{ number_format($item->discount_percentage, 2) }}%</td>
                        <td class="text-center">{{ number_format($item->discount_amount, 2) }} ج.م</td>
                        <td class="text-center"><strong>{{ number_format($item->total, 2) }} ج.م</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="summary">
            <div class="summary-row">
                <span>المجموع الفرعي:</span>
                <strong>{{ number_format($invoice->subtotal, 2) }} ج.م</strong>
            </div>
            <div class="summary-row">
                <span>إجمالي الخصومات:</span>
                <strong>{{ number_format($invoice->total_discount, 2) }} ج.م</strong>
            </div>
            <div class="summary-row">
                <span>بعد الخصم:</span>
                <strong>{{ number_format($invoice->total_after_discount, 2) }} ج.م</strong>
            </div>
            <div class="summary-row">
                <span>الضريبة:</span>
                <strong>{{ number_format($invoice->tax, 2) }} ج.م</strong>
            </div>
            <div class="summary-row">
                <span>الإجمالي:</span>
                <strong>{{ number_format($invoice->total, 2) }} ج.م</strong>
            </div>
            @if($invoice->status === 'final')
                <div class="summary-row">
                    <span>المدفوع:</span>
                    <strong style="color: #28a745;">{{ number_format($invoice->paid_amount, 2) }} ج.م</strong>
                </div>
                <div class="summary-row">
                    <span>المتبقي:</span>
                    <strong style="color: {{ $invoice->remaining_amount > 0 ? '#dc3545' : '#28a745' }};">
                        {{ number_format($invoice->remaining_amount, 2) }} ج.م
                    </strong>
                </div>
            @endif
        </div>
        
        @if($invoice->notes)
            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                <strong>ملاحظات:</strong>
                <p style="margin-top: 5px;">{{ $invoice->notes }}</p>
            </div>
        @endif
        
        @if($invoice->status === 'cancelled' && $invoice->cancellation_reason)
            <div style="margin-top: 20px; padding: 15px; background: #f8d7da; border-radius: 5px; color: #721c24;">
                <strong>سبب الإلغاء:</strong>
                <p style="margin-top: 5px;">{{ $invoice->cancellation_reason }}</p>
            </div>
        @endif
        
        <div class="footer">
            <p>شكراً لتعاملكم معنا</p>
            <p>تم إنشاء هذه الفاتورة في {{ $invoice->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>
</body>
</html>

