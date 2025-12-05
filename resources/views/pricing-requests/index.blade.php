@extends('layouts.app')

@section('title', 'طلبات التسعير')

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
                    طلبات التسعير
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Pricing Requests Content -->
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">
            <i class="fas fa-tags text-primary"></i> طلبات التسعير
        </h2>
        <div class="d-flex gap-3 align-items-center">
            <span class="badge bg-primary" style="font-size: 14px; padding: 8px 15px;">
                {{ $pricingRequests->total() }} طلب
            </span>
            <a href="{{ route('pricing-requests.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> طلب تسعير جديد
            </a>
        </div>
    </div>
    
    @if($pricingRequests->count() > 0)
        <div class="row">
            @foreach($pricingRequests as $request)
                <div class="col-12 mb-4">
                    <div class="order-card">
                        <div class="order-header">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                                <div>
                                    <h5 class="order-number mb-2">
                                        <i class="fas fa-receipt text-primary"></i>
                                        {{ $request->request_number }}
                                    </h5>
                                    <div class="order-date text-muted">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $request->created_at->format('d/m/Y') }}
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-clock"></i>
                                        {{ $request->created_at->format('H:i') }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    @php
                                        $statusLabels = [
                                            'pending' => ['قيد الانتظار', 'warning', 'fas fa-clock'],
                                            'priced' => ['تم التسعير', 'success', 'fas fa-check-circle'],
                                            'completed' => ['مكتمل', 'info', 'fas fa-check-double'],
                                            'cancelled' => ['ملغي', 'danger', 'fas fa-times-circle'],
                                        ];
                                        $status = $statusLabels[$request->status] ?? [$request->status, 'secondary', 'fas fa-info-circle'];
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
                                            <span class="value">{{ $request->items->count() }} منتج</span>
                                        </div>
                                    </div>
                                </div>
                                @if($request->status == 'priced')
                                    <div class="col-md-6">
                                        <div class="order-info-item">
                                            <i class="fas fa-money-bill-wave text-success"></i>
                                            <div>
                                                <span class="label">الإجمالي:</span>
                                                <span class="value fw-bold">{{ number_format($request->getTotalPrice(), 2) }} ج.م</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($request->notes)
                                    <div class="col-12">
                                        <div class="order-info-item">
                                            <i class="fas fa-sticky-note text-info"></i>
                                            <div>
                                                <span class="label">ملاحظات:</span>
                                                <span class="value">{{ $request->notes }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="order-footer">
                            <a href="{{ route('pricing-requests.show', $request->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> عرض التفاصيل
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $pricingRequests->links() }}
        </div>
    @else
        <div class="empty-orders text-center py-5">
            <div class="empty-icon mb-4">
                <i class="fas fa-tags"></i>
            </div>
            <h3 class="mb-3">لا توجد طلبات تسعير</h3>
            <p class="text-muted mb-4">لم تقم بإنشاء أي طلبات تسعير بعد</p>
            <a href="{{ route('pricing-requests.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> إنشاء طلب تسعير جديد
            </a>
        </div>
    @endif
</div>
@endsection

