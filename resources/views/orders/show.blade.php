@extends('layouts.app')

@section('title', 'تفاصيل الطلب')

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
                <li class="breadcrumb-item">
                    <a href="{{ route('orders.index') }}" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                        طلباتي
                    </a>
                </li>
                <li class="mx-1 text-muted">/</li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--dark-color); font-weight: 600;">
                    تفاصيل الطلب
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Order Details -->
<div class="container my-5">
    <!-- Order Header -->
    <div class="order-detail-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="order-detail-title mb-2">
                    <i class="fas fa-receipt text-primary"></i>
                    تفاصيل الطلب #{{ $order->order_number }}
                </h2>
                <div class="order-detail-date text-muted">
                    <i class="fas fa-calendar-alt"></i>
                    {{ $order->created_at->format('d/m/Y') }}
                    <span class="mx-2">•</span>
                    <i class="fas fa-clock"></i>
                    {{ $order->created_at->format('H:i') }}
                </div>
            </div>
            <div>
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
                <span class="order-detail-status badge bg-{{ $status[1] }}">
                    <i class="{{ $status[2] }}"></i>
                    {{ $status[0] }}
                </span>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Order Items -->
        <div class="col-lg-8 mb-4">
            <div class="order-detail-card">
                <div class="order-detail-card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-box text-primary"></i>
                        عناصر الطلب
                    </h5>
                    <span class="badge bg-primary">{{ $order->items->count() }} منتج</span>
                </div>
                <div class="order-detail-card-body">
                    <div class="order-items-list">
                        @foreach($order->items as $item)
                            <div class="order-item-card">
                                <div class="order-item-image">
                                    <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : asset('images/no-product.svg') }}" 
                                         alt="{{ $item->product->name }}"
                                         onerror="this.onerror=null; this.src='{{ asset('images/no-product.svg') }}';">
                                </div>
                                <div class="order-item-info">
                                    <h6 class="order-item-name">
                                        <a href="{{ route('products.show', $item->product->slug ?? $item->product->id) }}" class="text-decoration-none">
                                            {{ $item->product->name }}
                                        </a>
                                    </h6>
                                    <div class="order-item-meta">
                                        <span class="order-item-quantity">
                                            <i class="fas fa-layer-group"></i>
                                            الكمية: {{ $item->quantity }} {{ $item->product->unit ?? '' }}
                                        </span>
                                        <span class="order-item-unit-price">
                                            <i class="fas fa-tag"></i>
                                            سعر الوحدة: {{ number_format($item->unit_price, 2) }} ج.م
                                        </span>
                                    </div>
                                </div>
                                <div class="order-item-total">
                                    <strong>{{ number_format($item->total, 2) }} ج.م</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="order-summary">
                        <div class="order-summary-item">
                            <span>المجموع الفرعي:</span>
                            <span>{{ number_format($order->subtotal, 2) }} ج.م</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="order-summary-item text-success">
                                <span>
                                    <i class="fas fa-tag"></i>
                                    الخصم:
                                </span>
                                <span>-{{ number_format($order->discount, 2) }} ج.م</span>
                            </div>
                        @endif
                        @if($order->shipping_cost > 0)
                            <div class="order-summary-item">
                                <span>
                                    <i class="fas fa-truck"></i>
                                    مصاريف الشحن:
                                </span>
                                <span>{{ number_format($order->shipping_cost, 2) }} ج.م</span>
                            </div>
                        @endif
                        @if($order->tax > 0)
                            <div class="order-summary-item">
                                <span>
                                    <i class="fas fa-receipt"></i>
                                    الضريبة:
                                </span>
                                <span>{{ number_format($order->tax, 2) }} ج.م</span>
                            </div>
                        @endif
                        <div class="order-summary-total">
                            <span>المجموع الكلي:</span>
                            <span>{{ number_format($order->total, 2) }} ج.م</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Info Sidebar -->
        <div class="col-lg-4">
            <div class="order-info-card mb-4">
                <div class="order-info-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-info"></i>
                        معلومات الطلب
                    </h5>
                </div>
                <div class="order-info-body">
                    <div class="order-info-item">
                        <i class="fas fa-receipt text-primary"></i>
                        <div>
                            <span class="label">رقم الطلب</span>
                            <span class="value">{{ $order->order_number }}</span>
                        </div>
                    </div>
                    <div class="order-info-item">
                        <i class="fas fa-calendar-alt text-success"></i>
                        <div>
                            <span class="label">تاريخ الطلب</span>
                            <span class="value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="order-info-item">
                        <i class="fas fa-credit-card text-info"></i>
                        <div>
                            <span class="label">طريقة الدفع</span>
                            <span class="value">
                                @if($order->payment_method == 'cash')
                                    <i class="fas fa-money-bill-wave"></i> نقدي
                                @elseif($order->payment_method == 'bank_transfer')
                                    <i class="fas fa-university"></i> تحويل بنكي
                                @else
                                    {{ $order->payment_method ?? 'آجل' }}
                                @endif
                            </span>
                        </div>
                    </div>
                    @if($order->payment_status)
                        <div class="order-info-item">
                            <i class="fas fa-check-double text-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}"></i>
                            <div>
                                <span class="label">حالة الدفع</span>
                                <span class="value">
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">مدفوع</span>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="badge bg-warning">قيد الانتظار</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->payment_status }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                    @if($order->shipping_address)
                        <div class="order-info-item">
                            <i class="fas fa-map-marker-alt text-danger"></i>
                            <div>
                                <span class="label">عنوان الشحن</span>
                                <span class="value">{{ $order->shipping_address }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="order-actions-card">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="fas fa-arrow-right"></i>
                    العودة للطلبات
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
