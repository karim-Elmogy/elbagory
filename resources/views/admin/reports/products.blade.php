@extends('layouts.admin')

@section('title', 'تقرير المنتجات')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-cube"></i> تقرير المنتجات</h1>
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
                <option value="best_selling" {{ $type === 'best_selling' ? 'selected' : '' }}>الأكثر مبيعاً</option>
                <option value="least_selling" {{ $type === 'least_selling' ? 'selected' : '' }}>الأقل مبيعاً</option>
                <option value="low_stock" {{ $type === 'low_stock' ? 'selected' : '' }}>المخزون المنخفض</option>
            </select>
        </div>
        @if($type !== 'low_stock')
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
        @endif
    </form>
</div>

<div class="table-card">
    <h3>
        @if($type === 'best_selling')
            <i class="fas fa-star text-warning"></i> الأكثر مبيعاً
        @elseif($type === 'least_selling')
            <i class="fas fa-chart-line-down text-danger"></i> الأقل مبيعاً
        @else
            <i class="fas fa-exclamation-triangle text-danger"></i> المخزون المنخفض
        @endif
    </h3>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المنتج</th>
                    @if($type !== 'low_stock')
                        <th class="text-center">الكمية المباعة</th>
                        <th class="text-center">إجمالي الإيرادات</th>
                    @endif
                    <th class="text-center">السعر القطاعي</th>
                    <th class="text-center">السعر الجملة</th>
                    <th class="text-center">المخزون</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id ?? $product->id }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        @if($type !== 'low_stock')
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $product->total_sold ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <strong>{{ number_format($product->total_revenue ?? 0, 2) }} ج.م</strong>
                            </td>
                        @endif
                        <td class="text-center">{{ number_format($product->retail_price ?? $product->retail_price, 2) }} ج.م</td>
                        <td class="text-center">{{ number_format($product->wholesale_price ?? $product->wholesale_price, 2) }} ج.م</td>
                        <td class="text-center">
                            <span class="badge {{ ($product->stock_quantity ?? $product->stock_quantity) > 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ $product->stock_quantity ?? $product->stock_quantity }} {{ $product->unit ?? '' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $type === 'low_stock' ? '5' : '7' }}" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> لا توجد بيانات
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($type !== 'low_stock')
        <div class="d-flex justify-content-center mt-3">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection

