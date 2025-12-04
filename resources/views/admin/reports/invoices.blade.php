@extends('layouts.admin')

@section('title', 'تقرير الفواتير')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-file-invoice"></i> تقرير الفواتير</h1>
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
                <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="final" {{ $status === 'final' ? 'selected' : '' }}>نهائية</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>ملغاة</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">من تاريخ</label>
            <input type="date" name="date_from" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">إلى تاريخ</label>
            <input type="date" name="date_to" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100 mt-4">
                <i class="fas fa-search"></i> تطبيق
            </button>
        </div>
    </form>
</div>

<!-- الإحصائيات -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <h3><i class="fas fa-file-invoice"></i> إجمالي الفواتير</h3>
                <i class="fas fa-file-invoice stat-card-icon"></i>
            </div>
            <p class="number">{{ $totalInvoices }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="stat-card-header">
                <h3><i class="fas fa-money-bill-wave"></i> إجمالي المبلغ</h3>
                <i class="fas fa-money-bill-wave stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($totalAmount, 2) }} <small>ج.م</small></p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card info">
            <div class="stat-card-header">
                <h3><i class="fas fa-check-circle"></i> المدفوع</h3>
                <i class="fas fa-check-circle stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($paidAmount, 2) }} <small>ج.م</small></p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="stat-card-header">
                <h3><i class="fas fa-clock"></i> المتبقي</h3>
                <i class="fas fa-clock stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($remainingAmount, 2) }} <small>ج.م</small></p>
        </div>
    </div>
</div>

<!-- الفواتير حسب الحالة -->
<div class="table-card mb-4">
    <h3><i class="fas fa-chart-pie"></i> الفواتير حسب الحالة</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الحالة</th>
                    <th class="text-center">عدد الفواتير</th>
                    <th class="text-center">إجمالي المبلغ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoicesByStatus as $invoiceStatus)
                    <tr>
                        <td>
                            @switch($invoiceStatus->status)
                                @case('draft') مسودة @break
                                @case('final') نهائية @break
                                @case('cancelled') ملغاة @break
                                @default {{ $invoiceStatus->status }}
                            @endswitch
                        </td>
                        <td class="text-center">{{ $invoiceStatus->count }}</td>
                        <td class="text-center"><strong>{{ number_format($invoiceStatus->total, 2) }} ج.م</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- قائمة الفواتير -->
<div class="table-card">
    <h3><i class="fas fa-list"></i> تفاصيل الفواتير</h3>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>رقم الفاتورة</th>
                    <th>العميل</th>
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>الإجمالي</th>
                    <th>المتبقي</th>
                    <th class="text-center">إدارة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        <td>{{ $invoice->customer->name ?? 'غير معروف' }}</td>
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
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> لا توجد فواتير
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $invoices->appends(request()->query())->links() }}
    </div>
</div>
@endsection

