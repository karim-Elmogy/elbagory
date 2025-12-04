@extends('layouts.admin')

@section('title', 'إدارة العملاء')

@section('content')
<div class="page-header d-flex flex-wrap align-items-center justify-content-between gap-3">
    <h1><i class="fas fa-users"></i> إدارة العملاء</h1>
</div>

<div class="card shadow-sm p-3 mb-4">
    <form class="row g-3" method="GET">
        <div class="col-md-3">
            <label class="form-label">نوع العميل</label>
            <select name="type" class="form-select" onchange="this.form.submit()">
                <option value="">الكل</option>
                <option value="retail" {{ request('type') === 'retail' ? 'selected' : '' }}>قطاعي</option>
                <option value="wholesale" {{ request('type') === 'wholesale' ? 'selected' : '' }}>جملة</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">الكل</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>موقوف</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">بحث</label>
            <input type="text" name="search" class="form-control" placeholder="الاسم، الهاتف، أو البريد الإلكتروني" value="{{ request('search') }}">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> بحث
            </button>
        </div>
    </form>
</div>

@if($customers->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> لا توجد عملاء مطابقون.
    </div>
@else
    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>البريد الإلكتروني</th>
                        <th>النوع</th>
                        <th>الحالة</th>
                        <th>الرصيد</th>
                        <th>الحد الائتماني</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->id }}</td>
                            <td>
                                <strong>{{ $customer->name }}</strong>
                                @if($customer->company_name)
                                    <div class="text-muted small">{{ $customer->company_name }}</div>
                                @endif
                            </td>
                            <td>{{ $customer->phone }}</td>
                            <td>{{ $customer->email ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $customer->type === 'wholesale' ? 'bg-warning text-dark' : 'bg-info' }}">
                                    {{ $customer->type === 'wholesale' ? 'جملة' : 'قطاعي' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $customer->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $customer->status === 'active' ? 'نشط' : 'موقوف' }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted">-</span>
                            </td>
                            <td>
                                @if($customer->credit_limit)
                                    {{ number_format($customer->credit_limit, 2) }} ج.م
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $customers->appends(request()->query())->links() }}
        </div>
    </div>
@endif
@endsection

