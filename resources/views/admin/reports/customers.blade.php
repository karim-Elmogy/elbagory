@extends('layouts.admin')

@section('title', 'تقرير العملاء')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-users"></i> تقرير العملاء</h1>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<!-- الفلاتر -->
<div class="card shadow-sm p-3 mb-4">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label class="form-label">نوع التقرير</label>
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="top" {{ $type === 'top' ? 'selected' : '' }}>أفضل العملاء</option>
                <option value="new" {{ $type === 'new' ? 'selected' : '' }}>عملاء جدد</option>
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

<div class="table-card">
    <h3>
        @if($type === 'top')
            <i class="fas fa-trophy text-warning"></i> أفضل العملاء
        @else
            <i class="fas fa-user-plus text-success"></i> عملاء جدد
        @endif
    </h3>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم العميل</th>
                    <th>الهاتف</th>
                    <th>البريد</th>
                    <th>النوع</th>
                    @if($type === 'top')
                        <th class="text-center">عدد الطلبات</th>
                        <th class="text-center">إجمالي المشتريات</th>
                    @else
                        <th class="text-center">تاريخ التسجيل</th>
                    @endif
                    <th class="text-center">إدارة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td><strong>{{ $customer->name }}</strong></td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $customer->type === 'wholesale' ? 'bg-warning text-dark' : 'bg-info' }}">
                                {{ $customer->type === 'wholesale' ? 'جملة' : 'قطاعي' }}
                            </span>
                        </td>
                        @if($type === 'top')
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $customer->orders_count ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <strong>{{ number_format($customer->orders_sum_total ?? 0, 2) }} ج.م</strong>
                            </td>
                        @else
                            <td class="text-center">{{ $customer->created_at->format('Y-m-d') }}</td>
                        @endif
                        <td class="text-center">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $type === 'top' ? '8' : '7' }}" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> لا توجد بيانات
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-3">
        {{ $customers->appends(request()->query())->links() }}
    </div>
</div>
@endsection

