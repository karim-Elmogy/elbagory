@extends('layouts.admin')

@section('title', 'تقرير المخزون')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-warehouse"></i> تقرير المخزون</h1>
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-right"></i> رجوع
    </a>
</div>

<!-- الإحصائيات -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="stat-card primary">
            <div class="stat-card-header">
                <h3><i class="fas fa-boxes"></i> قيمة المخزون الإجمالية</h3>
                <i class="fas fa-boxes stat-card-icon"></i>
            </div>
            <p class="number">{{ number_format($totalInventoryValue, 2) }} <small>ج.م</small></p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card warning">
            <div class="stat-card-header">
                <h3><i class="fas fa-exclamation-triangle"></i> منتجات مخزون منخفض</h3>
                <i class="fas fa-exclamation-triangle stat-card-icon"></i>
            </div>
            <p class="number">{{ $lowStockProducts->count() }}</p>
        </div>
    </div>
</div>

<!-- حالة المخزون -->
<div class="table-card mb-4">
    <h3><i class="fas fa-chart-pie"></i> حالة المخزون</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الحالة</th>
                    <th class="text-center">عدد المنتجات</th>
                    <th class="text-center">إجمالي الكمية</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productsByStock as $stock)
                    <tr>
                        <td>
                            @switch($stock->stock_status)
                                @case('نفد المخزون')
                                    <span class="badge bg-danger">{{ $stock->stock_status }}</span>
                                    @break
                                @case('مخزون منخفض')
                                    <span class="badge bg-warning text-dark">{{ $stock->stock_status }}</span>
                                    @break
                                @case('مخزون متوسط')
                                    <span class="badge bg-info">{{ $stock->stock_status }}</span>
                                    @break
                                @case('مخزون جيد')
                                    <span class="badge bg-success">{{ $stock->stock_status }}</span>
                                    @break
                                @default
                                    {{ $stock->stock_status }}
                            @endswitch
                        </td>
                        <td class="text-center">{{ $stock->count }}</td>
                        <td class="text-center"><strong>{{ number_format($stock->total_stock, 0) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- المنتجات منخفضة المخزون -->
@if($lowStockProducts->count() > 0)
    <div class="table-card mb-4">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> المنتجات منخفضة المخزون</h3>
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>اسم المنتج</th>
                        <th>التصنيف</th>
                        <th class="text-center">المخزون الحالي</th>
                        <th class="text-center">حد إعادة الطلب</th>
                        <th class="text-center">الفرق</th>
                        <th class="text-center">إدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockProducts as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category->name ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-danger">{{ $product->stock_quantity }} {{ $product->unit }}</span>
                            </td>
                            <td class="text-center">{{ $product->reorder_level }} {{ $product->unit }}</td>
                            <td class="text-center">
                                <span class="text-danger">
                                    {{ $product->stock_quantity - $product->reorder_level }} {{ $product->unit }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- المنتجات الأكثر حركة -->
<div class="table-card">
    <h3><i class="fas fa-chart-line"></i> المنتجات الأكثر حركة</h3>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المنتج</th>
                    <th class="text-center">عدد الحركات</th>
                    <th class="text-center">إدارة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mostActiveProducts as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td><strong>{{ $product->name }}</strong></td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $product->movement_count }}</span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> لا توجد بيانات
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

