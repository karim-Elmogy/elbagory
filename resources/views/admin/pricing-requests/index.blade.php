@extends('layouts.admin')

@section('title', 'طلبات التسعير')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <h1><i class="fas fa-tags"></i> طلبات التسعير</h1>
</div>

<div class="card shadow-sm p-3 mb-4">
    <form class="row g-3" method="GET">
        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">الكل</option>
                <option value="pending" @selected(request('status') === 'pending')>قيد الانتظار</option>
                <option value="priced" @selected(request('status') === 'priced')>تم التسعير</option>
                <option value="completed" @selected(request('status') === 'completed')>مكتمل</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>ملغي</option>
            </select>
        </div>
        <div class="col-md-7">
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

@if($pricingRequests->isEmpty())
    <div class="alert alert-info">
        لا توجد طلبات تسعير مطابقة.
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
                        <th>عدد المنتجات</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pricingRequests as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td><strong>{{ $request->request_number }}</strong></td>
                            <td>
                                {{ $request->customer->name ?? 'غير معروف' }}
                                <div class="text-muted small">{{ $request->customer->phone ?? '' }}</div>
                            </td>
                            <td>{{ $request->items->count() }} منتج</td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-warning text-dark',
                                        'priced' => 'bg-success',
                                        'completed' => 'bg-info text-dark',
                                        'cancelled' => 'bg-danger',
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$request->status] ?? 'bg-secondary' }}">
                                    @switch($request->status)
                                        @case('pending') قيد الانتظار @break
                                        @case('priced') تم التسعير @break
                                        @case('completed') مكتمل @break
                                        @case('cancelled') ملغي @break
                                        @default غير معروف
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.pricing-requests.show', $request->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $pricingRequests->appends(request()->query())->links() }}
        </div>
    </div>
@endif
@endsection

