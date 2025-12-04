@extends('layouts.admin')

@section('title', 'إدارة الطلبات')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <h1><i class="fas fa-shopping-cart"></i> الطلبات</h1>
</div>

<div class="card shadow-sm p-3 mb-4">
    <form class="row g-3" method="GET">
        <div class="col-md-3">
            <label class="form-label">نوع الطلب</label>
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="">الكل</option>
                <option value="retail" @selected(request('type') === 'retail')>قطاعي</option>
                <option value="wholesale" @selected(request('type') === 'wholesale')>جملة</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">الكل</option>
                <option value="pending" @selected(request('status') === 'pending')>قيد المراجعة</option>
                <option value="processing" @selected(request('status') === 'processing')>جاري التجهيز</option>
                <option value="shipped" @selected(request('status') === 'shipped')>تم الشحن</option>
                <option value="delivered" @selected(request('status') === 'delivered')>تم التسليم</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>ملغي</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">بحث</label>
            <input type="text" name="search" class="form-control" placeholder="رقم الطلب أو اسم العميل" value="{{ request('search') }}">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">
                <i class="fas fa-search"></i> بحث
            </button>
        </div>
    </form>
</div>

@if($orders->isEmpty())
    <div class="alert alert-info">
        لا توجد طلبات مطابقة.
    </div>
@else
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>الإجمالي</th>
                        <th>تاريخ الإنشاء</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>
                                {{ $order->customer->name ?? 'زائر' }}
                                <div class="text-muted small">{{ $order->customer->phone ?? '' }}</div>
                            </td>
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
                                        @default غير معروف
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ number_format($order->total, 2) }} ج.م</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal -->
                        <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="statusModalLabel{{ $order->id }}">
                                                <i class="fas fa-sync me-2"></i> تحديث حالة الطلب #{{ $order->order_number }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="status{{ $order->id }}" class="form-label">الحالة الحالية: 
                                                    <span class="badge {{ $statusClasses[$order->status] ?? 'bg-secondary' }}">
                                                        @switch($order->status)
                                                            @case('pending') قيد المراجعة @break
                                                            @case('processing') جاري التجهيز @break
                                                            @case('shipped') تم الشحن @break
                                                            @case('delivered') تم التسليم @break
                                                            @case('cancelled') ملغي @break
                                                            @default غير معروف
                                                        @endswitch
                                                    </span>
                                                </label>
                                                <select name="status" id="status{{ $order->id }}" class="form-select form-select-lg" required>
                                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>جاري التجهيز</option>
                                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i> إلغاء
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-check me-1"></i> تحديث
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
@endif
@endsection

