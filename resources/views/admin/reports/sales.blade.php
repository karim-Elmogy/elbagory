@extends('layouts.admin')

@section('title', 'تقرير المبيعات')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-money-bill-wave"></i> تقرير المبيعات</h1>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<!-- الفلاتر -->
<div class="card shadow-sm p-3 mb-4">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">الفترة</label>
            <select name="period" class="form-select" onchange="this.form.submit()">
                <option value="day" {{ $period === 'day' ? 'selected' : '' }}>اليوم</option>
                <option value="week" {{ $period === 'week' ? 'selected' : '' }}>هذا الأسبوع</option>
                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>هذا الشهر</option>
                <option value="year" {{ $period === 'year' ? 'selected' : '' }}>هذه السنة</option>
                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>مخصص</option>
            </select>
        </div>
        @if($period === 'custom')
            <div class="col-md-3">
                <label class="form-label">من تاريخ</label>
                <input type="date" name="date_from" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">إلى تاريخ</label>
                <input type="date" name="date_to" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
            </div>
        @endif
        <div class="col-md-3">
            <label class="form-label">النوع</label>
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>الكل</option>
                <option value="retail" {{ $type === 'retail' ? 'selected' : '' }}>قطاعي</option>
                <option value="wholesale" {{ $type === 'wholesale' ? 'selected' : '' }}>جملة</option>
            </select>
        </div>
        @if($period === 'custom')
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 mt-4">
                    <i class="fas fa-search"></i> تطبيق
                </button>
            </div>
        @endif
    </form>
</div>

<!-- الإحصائيات -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card success">
            <div class="stat-card-header">
                <h3><i class="fas fa-money-bill-wave"></i> إجمالي المبيعات</h3>
                <i class="fas fa-money-bill-wave stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($totalSales, 2) }} <small>ج.م</small></p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <h3><i class="fas fa-shopping-bag"></i> عدد الطلبات</h3>
                <i class="fas fa-shopping-bag stat-card-icon"></i>
            </div>
            <p class="number">{{ $totalOrders }}</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card info">
            <div class="stat-card-header">
                <h3><i class="fas fa-calculator"></i> متوسط قيمة الطلب</h3>
                <i class="fas fa-calculator stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($averageOrderValue, 2) }} <small>ج.م</small></p>
        </div>
    </div>
</div>

<!-- المبيعات حسب الحالة -->
<div class="table-card mb-4">
    <h3><i class="fas fa-chart-pie"></i> المبيعات حسب الحالة</h3>
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
                @foreach($salesByStatus as $status => $data)
                    <tr>
                        <td>
                            @switch($status)
                                @case('pending') قيد المراجعة @break
                                @case('processing') جاري التجهيز @break
                                @case('shipped') تم الشحن @break
                                @case('delivered') تم التسليم @break
                                @case('cancelled') ملغي @break
                                @default {{ $status }}
                            @endswitch
                        </td>
                        <td class="text-center">{{ $data['count'] }}</td>
                        <td class="text-center"><strong>{{ number_format($data['total'], 2) }} ج.م</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- المبيعات حسب طريقة الدفع -->
<div class="table-card mb-4">
    <h3><i class="fas fa-credit-card"></i> المبيعات حسب طريقة الدفع</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>طريقة الدفع</th>
                    <th class="text-center">عدد الطلبات</th>
                    <th class="text-center">إجمالي المبيعات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesByPayment as $payment => $data)
                    <tr>
                        <td>
                            @switch($payment)
                                @case('cash') نقدي @break
                                @case('bank_transfer') تحويل بنكي @break
                                @case('credit') آجل @break
                                @default {{ $payment }}
                            @endswitch
                        </td>
                        <td class="text-center">{{ $data['count'] }}</td>
                        <td class="text-center"><strong>{{ number_format($data['total'], 2) }} ج.م</strong></td>
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
                </tr>
            </thead>
            <tbody>
                @foreach($orders->take(50) as $order)
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($orders->count() > 50)
        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle"></i> يتم عرض أول 50 طلب فقط. استخدم الفلاتر لعرض المزيد.
        </div>
    @endif
</div>
@endsection

