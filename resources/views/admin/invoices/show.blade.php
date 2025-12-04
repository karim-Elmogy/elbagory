@extends('layouts.admin')

@section('title', 'تفاصيل الفاتورة')

@section('content')
<div class="page-header d-flex justify-content-between flex-wrap align-items-center gap-3">
    <div>
        <h1><i class="fas fa-file-invoice"></i> فاتورة رقم {{ $invoice->invoice_number }}</h1>
        <div class="text-muted">تاريخ الفاتورة: {{ $invoice->invoice_date->format('Y-m-d') }}</div>
    </div>
    <div class="d-flex gap-2">
        @if($invoice->status === 'draft')
            <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> تعديل
            </a>
        @endif
        @if($invoice->status !== 'cancelled')
            <a href="{{ route('admin.invoices.print', $invoice->id) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-print"></i> طباعة
            </a>
        @endif
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- معلومات العميل -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">معلومات العميل</h5>
                <p class="mb-1"><strong>الاسم:</strong> {{ $invoice->customer->name ?? 'غير معروف' }}</p>
                <p class="mb-1"><strong>الهاتف:</strong> {{ $invoice->customer->phone ?? '-' }}</p>
                <p class="mb-1"><strong>البريد:</strong> {{ $invoice->customer->email ?? '-' }}</p>
                <p class="mb-1"><strong>العنوان:</strong> {{ $invoice->customer->address ?? '-' }}</p>
                @if($invoice->customer->company_name)
                    <p class="mb-1"><strong>اسم الشركة:</strong> {{ $invoice->customer->company_name }}</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- ملخص الدفع -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">ملخص الدفع</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>المجموع الفرعي:</span>
                    <strong>{{ number_format($invoice->subtotal, 2) }} ج.م</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>إجمالي الخصومات:</span>
                    <strong>{{ number_format($invoice->total_discount, 2) }} ج.م</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>بعد الخصم:</span>
                    <strong>{{ number_format($invoice->total_after_discount, 2) }} ج.م</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>الضريبة:</span>
                    <strong>{{ number_format($invoice->tax, 2) }} ج.م</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <span class="fs-5">الإجمالي:</span>
                    <strong class="fs-5 text-primary">{{ number_format($invoice->total, 2) }} ج.م</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>المدفوع:</span>
                    <strong class="text-success">{{ number_format($invoice->paid_amount, 2) }} ج.م</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>المتبقي:</span>
                    <strong class="text-{{ $invoice->remaining_amount > 0 ? 'danger' : 'success' }}">
                        {{ number_format($invoice->remaining_amount, 2) }} ج.م
                    </strong>
                </div>
            </div>
        </div>
    </div>
    
    <!-- إدارة الفاتورة -->
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">معلومات الفاتورة</h5>
                <p class="mb-1"><strong>طريقة الدفع:</strong>
                    @switch($invoice->payment_method)
                        @case('cash') نقدي @break
                        @case('bank_transfer') تحويل بنكي @break
                        @case('credit') آجل @break
                        @default {{ $invoice->payment_method }}
                    @endswitch
                </p>
                @if($invoice->credit_days)
                    <p class="mb-1"><strong>أيام الآجل:</strong> {{ $invoice->credit_days }} يوم</p>
                @endif
                <p class="mb-1"><strong>الحالة:</strong>
                    @php
                        $statusClasses = [
                            'draft' => 'bg-secondary',
                            'final' => 'bg-success',
                            'cancelled' => 'bg-danger',
                        ];
                    @endphp
                    <span class="badge {{ $statusClasses[$invoice->status] ?? 'bg-secondary' }}">
                        @switch($invoice->status)
                            @case('draft') مسودة @break
                            @case('final') نهائية @break
                            @case('cancelled') ملغاة @break
                            @default {{ $invoice->status }}
                        @endswitch
                    </span>
                </p>
                <p class="mb-1"><strong>أنشئ بواسطة:</strong> {{ $invoice->createdBy->name ?? '-' }}</p>
                <p class="mb-0"><strong>تاريخ الإنشاء:</strong> {{ $invoice->created_at->format('Y-m-d H:i') }}</p>
                
                @if($invoice->status === 'draft')
                    <hr>
                    <form method="POST" action="{{ route('admin.invoices.cancel', $invoice->id) }}" class="mt-3">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">سبب الإلغاء</label>
                            <textarea name="cancellation_reason" class="form-control" rows="2" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('هل أنت متأكد من إلغاء هذه الفاتورة؟')">
                            <i class="fas fa-times"></i> إلغاء الفاتورة
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- عناصر الفاتورة -->
<div class="table-card mt-4">
    <h3><i class="fas fa-boxes"></i> عناصر الفاتورة</h3>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>المنتج</th>
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
                        <td>{{ $index + 1 }}</td>
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
    </div>
</div>

@if($invoice->notes)
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">ملاحظات</h5>
            <p class="mb-0">{{ $invoice->notes }}</p>
        </div>
    </div>
@endif

@if($invoice->status === 'cancelled' && $invoice->cancellation_reason)
    <div class="alert alert-danger mt-4">
        <strong>سبب الإلغاء:</strong> {{ $invoice->cancellation_reason }}
    </div>
@endif

<!-- المدفوعات -->
@if($invoice->payments->count() > 0)
    <div class="table-card mt-4">
        <h3><i class="fas fa-money-bill-wave"></i> المدفوعات</h3>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المبلغ</th>
                        <th>تاريخ الدفع</th>
                        <th>طريقة الدفع</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->payments as $index => $payment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ number_format($payment->amount, 2) }} ج.م</strong></td>
                            <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td>{{ $payment->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

