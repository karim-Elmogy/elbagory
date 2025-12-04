@extends('layouts.admin')

@section('title', 'تفاصيل الطلب')

@section('content')
<div class="page-header d-flex justify-content-between flex-wrap align-items-center gap-3">
    <div>
        <h1><i class="fas fa-receipt"></i> طلب رقم {{ $order->order_number }}</h1>
        <div class="text-muted">تاريخ الطلب: {{ $order->created_at->format('Y-m-d H:i') }}</div>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">معلومات العميل</h5>
                <p class="mb-1"><strong>الاسم:</strong> {{ $order->customer->name ?? 'زائر' }}</p>
                <p class="mb-1"><strong>الهاتف:</strong> {{ $order->customer->phone ?? '-' }}</p>
                <p class="mb-1"><strong>النوع:</strong> {{ $order->type === 'wholesale' ? 'جملة' : 'قطاعي' }}</p>
                <p class="mb-1"><strong>الحالة:</strong>
                    <span class="badge bg-primary">{{ $order->status }}</span>
                </p>
                <p class="mb-0"><strong>طريقة الدفع:</strong> {{ $order->payment_method ?? '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">ملخص الدفع</h5>
                <p class="mb-1">المجموع الفرعي: {{ number_format($order->subtotal, 2) }} ج.م</p>
                <p class="mb-1">الخصم: {{ number_format($order->discount + $order->coupon_discount, 2) }} ج.م</p>
                <p class="mb-1">الشحن: {{ number_format($order->shipping_cost, 2) }} ج.م</p>
                <p class="mb-1">الضريبة: {{ number_format($order->tax, 2) }} ج.م</p>
                <hr>
                <p class="mb-0 fw-bold">الإجمالي: {{ number_format($order->total, 2) }} ج.م</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">إدارة الحالة</h5>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">الحالة الحالية</label>
                        <select name="status" class="form-select form-select-lg" required>
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>جاري التجهيز</option>
                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sync me-1"></i> تحديث الحالة
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="table-card mt-4">
    <h3><i class="fas fa-boxes"></i> عناصر الطلب</h3>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th class="text-center">الكمية</th>
                    <th class="text-center">سعر الوحدة</th>
                    <th class="text-center">الخصم</th>
                    <th class="text-center">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'منتج محذوف' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-center">{{ number_format($item->unit_price, 2) }} ج.م</td>
                        <td class="text-center">{{ number_format($item->discount, 2) }} ج.م</td>
                        <td class="text-center">{{ number_format($item->total, 2) }} ج.م</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if($order->notes)
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">ملاحظات</h5>
            <p class="mb-0">{{ $order->notes }}</p>
        </div>
    </div>
@endif
@endsection

