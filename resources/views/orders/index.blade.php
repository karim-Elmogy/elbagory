@extends('layouts.app')

@section('title', 'طلباتي')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 align-items-center" style="background: transparent;">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                        الرئيسية
                    </a>
                </li>
                <li class="mx-1 text-muted">/</li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--dark-color); font-weight: 600;">
                    طلباتي
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Orders Content -->
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-shopping-bag text-primary"></i> طلباتي
        </h2>
        <span class="badge bg-primary" style="font-size: 14px; padding: 8px 15px;">
            {{ $orders->total() }} طلب
        </span>
    </div>
    
    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-12 mb-4">
                    <div class="order-card">
                        <div class="order-header">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                                <div>
                                    <h5 class="order-number mb-2">
                                        <i class="fas fa-receipt text-primary"></i>
                                        {{ $order->order_number }}
                                    </h5>
                                    <div class="order-date text-muted">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $order->created_at->format('d/m/Y') }}
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-clock"></i>
                                        {{ $order->created_at->format('H:i') }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    @php
                                        $statusLabels = [
                                            'pending' => ['قيد المراجعة', 'warning', 'fas fa-clock'],
                                            'processing' => ['قيد التجهيز', 'info', 'fas fa-cog'],
                                            'shipped' => ['تم الشحن', 'primary', 'fas fa-truck'],
                                            'delivered' => ['تم التسليم', 'success', 'fas fa-check-circle'],
                                            'cancelled' => ['ملغي', 'danger', 'fas fa-times-circle'],
                                        ];
                                        $status = $statusLabels[$order->status] ?? [$order->status, 'secondary', 'fas fa-info-circle'];
                                    @endphp
                                    <span class="order-status badge bg-{{ $status[1] }}">
                                        <i class="{{ $status[2] }}"></i>
                                        {{ $status[0] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="order-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="order-info-item">
                                        <i class="fas fa-box text-primary"></i>
                                        <div>
                                            <span class="label">عدد المنتجات:</span>
                                            <span class="value">{{ $order->items->count() }} منتج</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="order-info-item">
                                        <i class="fas fa-money-bill-wave text-success"></i>
                                        <div>
                                            <span class="label">المجموع:</span>
                                            <span class="value fw-bold">{{ number_format($order->total, 2) }} ج.م</span>
                                        </div>
                                    </div>
                                </div>
                                @if($order->payment_method)
                                    <div class="col-md-6">
                                        <div class="order-info-item">
                                            <i class="fas fa-credit-card text-info"></i>
                                            <div>
                                                <span class="label">طريقة الدفع:</span>
                                                <span class="value">
                                                    @if($order->payment_method == 'cash')
                                                        نقدي
                                                    @elseif($order->payment_method == 'bank_transfer')
                                                        تحويل بنكي
                                                    @else
                                                        {{ $order->payment_method }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($order->payment_status)
                                    <div class="col-md-6">
                                        <div class="order-info-item">
                                            <i class="fas fa-check-double text-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}"></i>
                                            <div>
                                                <span class="label">حالة الدفع:</span>
                                                <span class="value">
                                                    @if($order->payment_status == 'paid')
                                                        مدفوع
                                                    @elseif($order->payment_status == 'pending')
                                                        قيد الانتظار
                                                    @else
                                                        {{ $order->payment_status }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="order-footer">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="empty-orders text-center py-5">
            <div class="empty-icon mb-4">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3 class="mb-3">لا توجد طلبات</h3>
            <p class="text-muted mb-4">لم تقم بإنشاء أي طلبات بعد</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag"></i> ابدأ التسوق
            </a>
        </div>
    @endif
</div>
@endsection
