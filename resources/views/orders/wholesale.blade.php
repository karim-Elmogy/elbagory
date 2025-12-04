@extends('layouts.app')

@section('title', 'طلبات الجملة')

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
                    طلبات الجملة
                </li>
            </ol>
        </nav>
    </div>
</div>

<!-- Orders Content -->
<div class="container">
    <h2 class="mb-4">طلبات الجملة</h2>
    
    @if($orders->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>التاريخ</th>
                                <th>الحالة</th>
                                <th>المجموع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @php
                                            $statusLabels = [
                                                'pending' => ['قيد المراجعة', 'warning'],
                                                'processing' => ['قيد التجهيز', 'info'],
                                                'shipped' => ['تم الشحن', 'primary'],
                                                'delivered' => ['تم التسليم', 'success'],
                                                'cancelled' => ['ملغي', 'danger'],
                                            ];
                                            $status = $statusLabels[$order->status] ?? [$order->status, 'secondary'];
                                        @endphp
                                        <span class="badge bg-{{ $status[1] }}">{{ $status[0] }}</span>
                                    </td>
                                    <td><strong>{{ number_format($order->total, 2) }} ج.م</strong></td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> عرض التفاصيل
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-warehouse" style="font-size: 80px; color: #ccc; margin-bottom: 20px;"></i>
            <h3>لا توجد طلبات جملة</h3>
            <p class="text-muted mb-4">لم تقم بإنشاء أي طلبات جملة بعد</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag"></i> ابدأ التسوق
            </a>
        </div>
    @endif
</div>
@endsection

