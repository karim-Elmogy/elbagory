@extends('layouts.admin')

@section('title', 'فواتير الجملة')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <h1><i class="fas fa-file-invoice"></i> فواتير الجملة</h1>
    <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> إنشاء فاتورة جديدة
    </a>
</div>

<div class="card shadow-sm p-3 mb-4">
    <form class="row g-3" method="GET">
        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">الكل</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>مسودة</option>
                <option value="final" {{ request('status') === 'final' ? 'selected' : '' }}>نهائية</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>ملغاة</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">من تاريخ</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">إلى تاريخ</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">بحث</label>
            <input type="text" name="search" class="form-control" placeholder="رقم الفاتورة أو اسم العميل" value="{{ request('search') }}">
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> بحث
            </button>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> إعادة تعيين
            </a>
        </div>
    </form>
</div>

@if($invoices->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> لا توجد فواتير مطابقة.
    </div>
@else
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>رقم الفاتورة</th>
                        <th>العميل</th>
                        <th>التاريخ</th>
                        <th>طريقة الدفع</th>
                        <th>الحالة</th>
                        <th>الإجمالي</th>
                        <th>المتبقي</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->id }}</td>
                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                            <td>
                                {{ $invoice->customer->name ?? 'غير معروف' }}
                                <div class="text-muted small">{{ $invoice->customer->phone ?? '' }}</div>
                            </td>
                            <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                            <td>
                                @switch($invoice->payment_method)
                                    @case('cash') نقدي @break
                                    @case('bank_transfer') تحويل بنكي @break
                                    @case('credit') آجل @break
                                    @default {{ $invoice->payment_method }}
                                @endswitch
                            </td>
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
                                <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($invoice->status === 'draft')
                                    <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                @endif
                                @if($invoice->status !== 'cancelled')
                                    <a href="{{ route('admin.invoices.print', $invoice->id) }}" class="btn btn-sm btn-outline-info" title="طباعة" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $invoices->appends(request()->query())->links() }}
        </div>
    </div>
@endif
@endsection

