@extends('layouts.admin')

@section('title', 'تفاصيل العميل')

@section('content')
<div class="page-header d-flex justify-content-between flex-wrap align-items-center gap-3">
    <div>
        <h1><i class="fas fa-user"></i> {{ $customer->name }}</h1>
        <div class="text-muted">رقم العميل: #{{ $customer->id }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> تعديل
        </a>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- معلومات العميل -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">معلومات العميل</h5>
                <div class="row mb-3">
                    <div class="col-4"><strong>الاسم:</strong></div>
                    <div class="col-8">{{ $customer->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><strong>الهاتف:</strong></div>
                    <div class="col-8">{{ $customer->phone }}</div>
                </div>
                @if($customer->email)
                    <div class="row mb-3">
                        <div class="col-4"><strong>البريد:</strong></div>
                        <div class="col-8">{{ $customer->email }}</div>
                    </div>
                @endif
                <div class="row mb-3">
                    <div class="col-4"><strong>النوع:</strong></div>
                    <div class="col-8">
                        <span class="badge {{ $customer->type === 'wholesale' ? 'bg-warning text-dark' : 'bg-info' }}">
                            {{ $customer->type === 'wholesale' ? 'جملة' : 'قطاعي' }}
                        </span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4"><strong>الحالة:</strong></div>
                    <div class="col-8">
                        <span class="badge {{ $customer->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                            {{ $customer->status === 'active' ? 'نشط' : 'موقوف' }}
                        </span>
                    </div>
                </div>
                @if($customer->company_name)
                    <div class="row mb-3">
                        <div class="col-4"><strong>اسم الشركة:</strong></div>
                        <div class="col-8">{{ $customer->company_name }}</div>
                    </div>
                @endif
                @if($customer->tax_number)
                    <div class="row mb-3">
                        <div class="col-4"><strong>الرقم الضريبي:</strong></div>
                        <div class="col-8">{{ $customer->tax_number }}</div>
                    </div>
                @endif
                @if($customer->address)
                    <div class="row mb-3">
                        <div class="col-4"><strong>العنوان:</strong></div>
                        <div class="col-8">{{ $customer->address }}</div>
                    </div>
                @endif
                @if($customer->detailed_address)
                    <div class="row mb-3">
                        <div class="col-4"><strong>العنوان التفصيلي:</strong></div>
                        <div class="col-8">{{ $customer->detailed_address }}</div>
                    </div>
                @endif
                @if($customer->preferred_payment_method)
                    <div class="row mb-3">
                        <div class="col-4"><strong>طريقة الدفع المفضلة:</strong></div>
                        <div class="col-8">
                            @switch($customer->preferred_payment_method)
                                @case('cash') نقدي @break
                                @case('bank_transfer') تحويل بنكي @break
                                @case('credit') آجل @break
                                @default {{ $customer->preferred_payment_method }}
                            @endswitch
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- الإحصائيات -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title mb-3">الإحصائيات</h5>
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="stat-card primary p-3 text-center">
                            <h3 class="mb-1">{{ $totalOrders }}</h3>
                            <p class="mb-0 small">إجمالي الطلبات</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card success p-3 text-center">
                            <h3 class="mb-1">{{ $totalInvoices }}</h3>
                            <p class="mb-0 small">إجمالي الفواتير</p>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="stat-card info p-3 text-center">
                            <h3 class="mb-1">{{ number_format($totalSpent, 2) }} ج.م</h3>
                            <p class="mb-0 small">إجمالي المشتريات</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <h4 class="mb-1 text-muted">-</h4>
                            <p class="mb-0 small">الرصيد</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded text-center">
                            <h4 class="mb-1">
                                @if($customer->credit_limit)
                                    {{ number_format($customer->credit_limit, 2) }} ج.م
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </h4>
                            <p class="mb-0 small">الحد الائتماني</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الطلبات -->
@if($customer->orders->count() > 0)
    <div class="table-card mt-4">
        <h3><i class="fas fa-shopping-cart"></i> الطلبات</h3>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>الإجمالي</th>
                        <th>تاريخ الإنشاء</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->orders->take(10) as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
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
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($customer->orders->count() > 10)
            <div class="text-center mt-3">
                <a href="{{ route('admin.orders.index', ['search' => $customer->name]) }}" class="btn btn-outline-primary">
                    عرض جميع الطلبات
                </a>
            </div>
        @endif
    </div>
@endif

<!-- الفواتير -->
@if($customer->invoices->count() > 0)
    <div class="table-card mt-4">
        <h3><i class="fas fa-file-invoice"></i> الفواتير</h3>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>رقم الفاتورة</th>
                        <th>التاريخ</th>
                        <th>الحالة</th>
                        <th>الإجمالي</th>
                        <th>المتبقي</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->invoices->take(10) as $invoice)
                        <tr>
                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                            <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'draft' => 'bg-secondary',
                                        'final' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                    ];
                                @endphp
                                <span class="badge {{ $statusClasses[$invoice->status] ?? 'bg-secondary' }}">
                                    @switch($invoice->status)
                                        @case('draft') مسودة @break
                                        @case('final') نهائية @break
                                        @case('cancelled') ملغاة @break
                                        @default {{ $invoice->status }}
                                    @endswitch
                                </span>
                            </td>
                            <td><strong>{{ number_format($invoice->total, 2) }} ج.م</strong></td>
                            <td>
                                @if($invoice->status === 'final')
                                    <span class="text-{{ $invoice->remaining_amount > 0 ? 'danger' : 'success' }}">
                                        {{ number_format($invoice->remaining_amount, 2) }} ج.م
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($customer->invoices->count() > 10)
            <div class="text-center mt-3">
                <a href="{{ route('admin.invoices.index', ['search' => $customer->name]) }}" class="btn btn-outline-primary">
                    عرض جميع الفواتير
                </a>
            </div>
        @endif
    </div>
@endif
@endsection

