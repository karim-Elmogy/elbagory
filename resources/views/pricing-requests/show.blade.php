@extends('layouts.app')

@section('title', 'تفاصيل طلب التسعير')

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
                    <a href="{{ route('pricing-requests.index') }}" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                        طلبات التسعير
                    </a>
                </li>
                <li class="mx-1 text-muted">/</li>
                <li class="breadcrumb-item active" aria-current="page" style="color: var(--dark-color); font-weight: 600;">
                    {{ $pricingRequest->request_number }}
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Pricing Request Details -->
<div class="container my-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt"></i> طلب التسعير #{{ $pricingRequest->request_number }}
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Request Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-calendar text-primary"></i> تاريخ الإنشاء:</strong>
                                <span>{{ $pricingRequest->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item mb-3">
                                <strong><i class="fas fa-info-circle text-primary"></i> الحالة:</strong>
                                @php
                                    $statusLabels = [
                                        'pending' => ['قيد الانتظار', 'warning'],
                                        'priced' => ['تم التسعير', 'success'],
                                        'completed' => ['مكتمل', 'info'],
                                        'cancelled' => ['ملغي', 'danger'],
                                    ];
                                    $status = $statusLabels[$pricingRequest->status] ?? [$pricingRequest->status, 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $status[1] }}">{{ $status[0] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <h5 class="mb-3">
                        <i class="fas fa-box text-primary"></i> المنتجات
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>اسم المنتج</th>
                                    <th>الكمية</th>
                                    <th>الوحدة</th>
                                    <th>السعر</th>
                                    <th>الإجمالي</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pricingRequest->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit ?? '-' }}</td>
                                        <td>
                                            @if($item->price !== null)
                                                {{ number_format($item->price, 2) }} ج.م
                                            @else
                                                <span class="text-muted">قيد التسعير</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->price !== null)
                                                {{ number_format($item->getTotalPrice(), 2) }} ج.م
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if($pricingRequest->status == 'priced')
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>الإجمالي:</strong></td>
                                        <td colspan="2"><strong>{{ number_format($pricingRequest->getTotalPrice(), 2) }} ج.م</strong></td>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

                    <!-- Notes -->
                    @if($pricingRequest->notes)
                        <div class="mt-4">
                            <h5 class="mb-2">
                                <i class="fas fa-sticky-note text-primary"></i> ملاحظاتك
                            </h5>
                            <p class="text-muted">{{ $pricingRequest->notes }}</p>
                        </div>
                    @endif

                    @if($pricingRequest->admin_notes)
                        <div class="mt-4">
                            <h5 class="mb-2">
                                <i class="fas fa-comment-alt text-info"></i> ملاحظات الإدمن
                            </h5>
                            <p class="text-muted">{{ $pricingRequest->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> معلومات الطلب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>رقم الطلب:</strong><br>
                        <span class="text-muted">{{ $pricingRequest->request_number }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>عدد المنتجات:</strong><br>
                        <span class="text-muted">{{ $pricingRequest->items->count() }} منتج</span>
                    </div>
                    @if($pricingRequest->status == 'priced')
                        <div class="mb-3">
                            <strong>الإجمالي:</strong><br>
                            <span class="text-success fw-bold">{{ number_format($pricingRequest->getTotalPrice(), 2) }} ج.م</span>
                        </div>
                    @endif
                    <div class="mb-3">
                        <strong>الحالة:</strong><br>
                        @php
                            $status = $statusLabels[$pricingRequest->status] ?? [$pricingRequest->status, 'secondary'];
                        @endphp
                        <span class="badge bg-{{ $status[1] }}">{{ $status[0] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

