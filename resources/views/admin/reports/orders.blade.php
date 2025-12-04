@extends('layouts.admin')

@section('title', 'تقرير الطلبات')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-shopping-cart"></i> تقرير الطلبات</h1>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<!-- الفلاتر -->
<div class="card shadow-sm p-3 mb-4">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>الكل</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>جاري التجهيز</option>
                <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">النوع</label>
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>الكل</option>
                <option value="retail" {{ $type === 'retail' ? 'selected' : '' }}>قطاعي</option>
                <option value="wholesale" {{ $type === 'wholesale' ? 'selected' : '' }}>جملة</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">من تاريخ</label>
            <input type="date" name="date_from" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">إلى تاريخ</label>
            <input type="date" name="date_to" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 mt-4">
                <i class="fas fa-search"></i> تطبيق
            </button>
        </div>
    </form>
</div>

<!-- الإحصائيات -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <h3><i class="fas fa-shopping-bag"></i> إجمالي الطلبات</h3>
                <i class="fas fa-shopping-bag stat-card-icon"></i>
            </div>
            <p class="number">{{ $totalOrders }}</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card success">
            <div class="stat-card-header">
                <h3><i class="fas fa-money-bill-wave"></i> إجمالي المبيعات</h3>
                <i class="fas fa-money-bill-wave stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($totalAmount, 2) }} <small>ج.م</small></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card info">
            <div class="stat-card-header">
                <h3><i class="fas fa-calculator"></i> متوسط قيمة الطلب</h3>
                <i class="fas fa-calculator stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($totalOrders > 0 ? $totalAmount / $totalOrders : 0, 2) }} <small>ج.م</small></p>
        </div>
    </div>
</div>

<!-- الطلبات حسب الحالة -->
<div class="table-card mb-4">
    <h3><i class="fas fa-chart-pie"></i> الطلبات حسب الحالة</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الحالة</th>
                    <th class="text-center">عدد الطلبات</th>
                    <th class="text-center">إجمالي المبيعات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordersByStatus as $orderStatus)
                    <tr>
                        <td>
                            @switch($orderStatus->status)
                                @case('pending') قيد المراجعة @break
                                @case('processing') جاري التجهيز @break
                                @case('shipped') تم الشحن @break
                                @case('delivered') تم التسليم @break
                                @case('cancelled') ملغي @break
                                @default {{ $orderStatus->status }}
                            @endswitch
                        </td>
                        <td class="text-center">{{ $orderStatus->count }}</td>
                        <td class="text-center"><strong>{{ number_format($orderStatus->total, 2) }} ج.م</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- قائمة الطلبات -->
<div class="table-card">
    <h3><i class="fas fa-list"></i> تفاصيل الطلبات</h3>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>العميل</th>
                    <th>النوع</th>
                    <th>الحالة</th>
                    <th>الإجمالي</th>
                    <th>التاريخ</th>
                    <th class="text-center">إدارة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>{{ $order->order_number }}</strong></td>
                        <td>{{ $order->customer->name ?? 'زائر' }}</td>
                        <td>
                            <span class="badge {{ $order->type === 'wholesale' ? 'bg-warning text-dark' : 'bg-info' }}">
                                {{ $order->type === 'wholesale' ? 'جملة' : 'قطاعي' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-secondary',
                                    'processing' => 'bg-primary',
                                    'shipped' => 'bg-info text-dark',
                                    'delivered' => 'bg-success',
                                    'cancelled' => 'bg-danger',
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }}">
                                @switch($order->status)
                                    @case('pending') قيد المراجعة @break
                                    @case('processing') جاري التجهيز @break
                                    @case('shipped') تم الشحن @break
                                    @case('delivered') تم التسليم @break
                                    @case('cancelled') ملغي @break
                                    @default {{ $order->status }}
                                @endswitch
                            </span>
                        </td>
                        <td><strong>{{ number_format($order->total, 2) }} ج.م</strong></td>
                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> لا توجد طلبات
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection

